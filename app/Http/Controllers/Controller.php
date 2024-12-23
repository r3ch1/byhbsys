<?php

namespace App\Http\Controllers;

abstract class Controller
{
    protected $principalModelClass;
    protected $enableOnlyCreatorCanDelete = false;
    protected $errors = [
        'PERMISSION_DENIED' => 'Only creators can delete'
    ];

    protected function getErrors()
    {
        return $this->errors;
    }

    public function index()
    {
        $data = new $this->principalModelClass();
        if (!empty(request()->toArray())) {
            $data = $data->where(request()->toArray());
        }
        // return responseOk($data->paginate());
        return response()->json(['success' => true, 'data' => $data->paginate()]);
    }

    protected function canDestroy($data)
    {
        if ($this->enableOnlyCreatorCanDelete && auth()->id() != $data->createdHistory->user_id) {
            throw new Exception('PERMISSION_DENIED', 403);
        }
        return true;
    }

    public function destroy($id)
    {
        $model = new $this->principalModelClass();
        try {
            $data = $model->with('createdHistory')->find($id);
            $this->canDestroy($data);
            $data->delete();
            return responseOk([]);
        } catch(Throwable $t) {
            $message = '';
            if (array_key_exists($t->getMessage(), $this->getErrors())) {
                $message = $this->errors[$t->getMessage()];
            }
            return responseFail([], $message, $t->getCode());
        }
    }

    public function store()
    {
        $model = new $this->principalModelClass();
        $dataToInsert = request($model->getFillable());

        if (in_array('created_by', $model->getFillable())) {
            $dataToInsert['created_by'] = request()->user()->id;
        }
        $data = $model->create($dataToInsert);
        return response()->json(['success' => true, 'data' => $data], 201);
    }
}
