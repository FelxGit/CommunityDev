<?php

namespace App\Repositories;

use Carbon\Carbon;
use App\Helpers\Globals;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use App\Interfaces\PostRepositoryInterface;
use App\Models\Post;

class PostEloquentRepository extends MainEloquentRepository implements PostRepositoryInterface
{
    /*======================================================================
     * PROPERTIES
     *======================================================================*/

    /**
     * @var Post $Model
     */
    public $Model = Post::class;

    /*======================================================================
     * METHODS
     *======================================================================*/

    /**
     * acquire all model records
     * NTC (No Try Catch) method
     *
     * @return Collection
     */
    public function NTCacquireAll()
    {
        $rtn = $this->arrayToCollection([]);

        if (!empty($this->Model)) {
            $rtn = $this->Model::with(['user', 'category', 'tags'])
                ->orderBy('created_at', 'desc')->get();
        }

        return $rtn;
    }

    public function acquire($id)
    {
        return parent::acquire($id);
    }

    public function NTCacquire($id)
    {
        $rtn = false;

        if (!empty($this->Model) && !empty($id)) {
            $rtn = $this->Model::with(['user', 'category', 'tags', 'likes', 'favorites', 'comments'])
                ->where('id', $id)
                ->first();
        }

        if (empty($rtn)) {
            $rtn = $this->Model::empty();
        }

        return $rtn;
    }

    public function acquireAllByDisplayTypeAndCategory(array $data)
    {
        $rtn = $this->arrayToCollection([]);

        if (!empty($this->Model)) {
            if (!empty($data['categoryTitle'])) {
                $rtn = $this->Model::whereHas('category', function (Builder $query) use ($data) {
                    if (!empty($data['categoryTitle'])) {
                        $query->where('title', $data['categoryTitle']);
                    }
                });
            } else {
                $rtn = new $this->Model;
            }

            if ($data['post_display_type'] == Post::POST_DISPLAY_TOP) { // need likes
                $rtn = $rtn->withCount('likes')
                    ->orderBy('likes_count', 'desc');
            } else if ($data['post_display_type'] == Post::POST_DISPLAY_LATEST) {
                $rtn = $rtn->orderBy('created_at', 'desc');
            } else {
                $rtn = $rtn->orderBy('created_at', 'desc');
            }

            $rtn = $rtn->with(['user', 'category', 'tags', 'likes', 'favorites', 'comments'])->paginate(10);
        }

        return $rtn;
    }

    public function acquireByUserFavoritePosts($ids)
    {
        $rtn = $this->arrayToCollection([]);

        $implodedIds = implode(',', array_values($ids));
        if (!empty($this->Model)) {
            $rtn = $this->Model::whereIn('id', $ids)
                ->orderByRaw(\DB::raw("FIELD(id, $implodedIds)"))
                ->with(['user', 'category', 'tags', 'likes', 'favorites', 'comments'])
                ->paginate(Globals::mTag()::PAGINATE_COUNT);
        }

        return $rtn;
    }

    public function acquireByAttributes(array $attributes, bool $returnCollection = true)
    {
        return parent::acquireByAttributes($attributes, $returnCollection);
    }

    public function adjust(int $id, array $attributes)
    {
        return parent::adjust($id, $attributes);
    }

    public function annul(int $id)
    {
        return parent::annul($id);
    }
    /**
     * add a Post record
     *
     * @param Array $attributes
     * @return Bool/Post
     */
    public function add(array $attributes)
    {
        $rtn = false;

        try {
            $rtn = $this->NTCadd($attributes);
        } catch (\Exception $e) {
            \Log::error('Exception: ' . $e->getMessage());
        }

        return $rtn;
    }

    /**
     * add a model record
     * NTC (No Try Catch) method
     *
     * @param Array $attributes
     * @return Bool/Model
     */
    public function NTCadd(array $attributes)
    {
        $rtn = false;

        if (!empty($this->Model) && count($attributes) != 0) {
            $rtn = $this->Model::create($attributes);

            if ($rtn) {
                $rtn = $rtn->fresh();
            }
        }

        return $rtn;
    }
}
