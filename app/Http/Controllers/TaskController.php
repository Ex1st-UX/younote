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
            'img' => ($img) ? ImageController::handleImages($img) : ''
        ];
        $taskId = $this->save($data);

        $taskResponseData = $this->getTaskById($taskId, false);
        return response()->json($taskResponseData);
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
        } else {
            return false;
        }
    }

    public function getTaskList(Request $request, $orderBy): \Illuminate\Http\JsonResponse
    {
        $userId = Auth::id();
        $input = $request->input();

        if ($input) {
            $tasks = Task::query();

            if ($input['search_q']) {
                $tasks->where('title', 'like', '%' . $input['search_q'] . '%');
            }
            if ($input['filter__start_date']) {
                $tasks->where('created_at', '>=', $input['filter__start_date']);
            }
            if ($input['filter__end_date']) {
                $tasks->where('created_at', '<=', $input['filter__end_date']);
            }
            if ($input['filter__tags']) {
                $tasks->whereHas('tags', function ($query) use ($input) {
                    $query->whereIn('title', [$input['filter__tags']]);
                });
            }

            $tasks = $tasks->orderBy($orderBy, 'desc')->get();
        } else {
            $tasks = Task::where('user_id', $userId)
                ->orderBy($orderBy, 'desc')
                ->get();
        }

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

        return response()->json(['items' => $arResponse]);
    }

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
            $previewImage = ImageController::getPreview($pathImage, ImageController::TASK_PREVIEW_SIZE);

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
        } else {
            return $data;
        }
    }

    public static function getTags(): array
    {
        $Tags = Tags::all();

        $arData = [];
        foreach ($Tags as $tag) {
            $arData[] = $tag->title;
        }

        return $arData;
    }
}
