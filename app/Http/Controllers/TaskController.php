<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;
use App\Models\Image;
use App\Models\Tags;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class TaskController extends Controller
{
    const TASK_PREVIEW_SIZE = [150, 150];

    public function create(Request $request): \Illuminate\Http\JsonResponse
    {
        $img = $request->file('image_task__input');
        if (!is_array($img) && !empty($img)) {
            $img = [$img];
        }

        $data = [
            'title' => $request->input('title_task__input'),
            'text' => $request->input('text_task__input'),
            'tags_list' => $request->input('tag_task__input'),
            'img' => ($img) ? $this->handleImages($img) : ''
        ];
        $taskId = $this->save($data);

        $taskResponseData = $this->getTaskById($taskId, false);
        return response()->json(['success' => boolval($taskId), 'data' => $taskResponseData]);
    }

    protected function save(array $data): mixed
    {
        $Task = new Task;
        $arImages = $data['img'];
        $arTags = $data['tags_list'];

        $Task->title = $data['title'];
        $Task->text = $data['text'];
        $Task->user_id = Auth::id();
        $res = $Task->save();

        if ($res && !empty($arImages)) {
            foreach ($arImages as $image) {
                $Image = new Image;
                $Image->task_id = $Task->id;
                $Image->path = $image['picture'];
                $Image->save();
            }
        }

        if ($res && !empty($arTags)) {
            foreach ($arTags as $tag) {
                $Tags = new Tags;
                $Tags->task_id = $Task->id;
                $Tags->title = $tag;
                $Tags->save();
            }
        }

        if ($res) {
            return $Task->id;
        }
        else {
            return false;
        }
    }

    public function getTaskList(): \Illuminate\Http\JsonResponse
    {
        $userId = Auth::id();
        $tasks = Task::where('user_id', $userId)->orderBy('updated_at', 'desc')->get();

        $arResponse = [];
        foreach ($tasks as $task) {
            $arTags = [];

            foreach ($task->tags as $tag) {
                $arTags[] = $tag->title;
            }

            $arResponse[] = [
                'id' => $task->id,
                'title' => $task->title,
                'text' => $task->text,
                'date' => $task->updated_at,
                'tags_list' => $arTags
            ];
        }

        return response()->json($arResponse);
    }

    public function

    /**
     * @param int $taskId
     * @param $respJson - return JSON or VARIABLE (ARRAY)
     */
    public function getTaskById(int $taskId, $respJson = true)
    {
        $arImages = [];
        $Task = Task::where('id', $taskId)->first();

        foreach ($Task->images as $image) {
            $pathImage = $image->path;
            $previewImage = ImageController::getPreview($pathImage, self::TASK_PREVIEW_SIZE);

            $arImages[] = [
                'picture' => Storage::url('images/' . $pathImage),
                'preview' => Storage::url($previewImage)
            ];
        }

        $arTags = [];
        foreach ($Task->tags as $tag) {
            $arTags[] = $tag->title;
        }

        $data = [
            'title' => $Task->title,
            'text' => $Task->text,
            'tags_list' => $arTags,
            'date' => $Task->updated_at,
            'img' => $arImages
        ];

        if ($respJson) {
            return response()->json($data);
        }
        else {
            return $data;
        }
    }

    protected function handleImages($images): array
    {
        foreach ($images as $item) {
            if (ImageController::isImageValid($item)) {
                $path = $item->store('public/images');
                $previewPath = ImageController::getPreview($path, self::TASK_PREVIEW_SIZE);
                $imageName = basename($path);

                if ($path) {
                    $arPathes[$imageName] = [
                        'picture' => $imageName,
                        'preview' => $previewPath
                    ];
                }
            }
        }

        return $arPathes;
    }
}
