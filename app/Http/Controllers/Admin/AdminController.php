<?php

namespace App\Http\Controllers\Admin;

abstract class AdminController extends \App\Http\Controllers\Controller
{
    protected string $viewPath = 'admin';
    protected string $routePrefix = 'admin.';
    protected array $resourceRoutes = ['index', 'create', 'store', 'show', 'edit', 'update', 'destroy'];
    protected int $perPage = 20;
    protected array $validateStore = [];
    protected array $validateUpdate = [];

    public function index()
    {
        $model = $this->model();

        $items = $model->when($this->searchable(), function ($query, $search) {
            $this->applySearch($query, $search);
        })->when(!empty($this->filters()), function ($query) {
            $this->applyFilters($query);
        })->paginate($this->perPage);

        return view("{$this->viewPath}.index", compact('items'));
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $validated = $this->validated($request, 'store');

        $this->model()->create($validated);

        return redirect()
            ->route("{$this->routePrefix}index")
            ->with('success', 'Registro criado com sucesso.');
    }

    public function update(\Illuminate\Http\Request $request, $id)
    {
        $record = $this->findRecord($id);
        $validated = $this->validated($request, 'update');

        $record->update($validated);

        return redirect()
            ->route("{$this->routePrefix}index")
            ->with('success', 'Registro atualizado com sucesso.');
    }

    public function destroy($id)
    {
        $record = $this->findRecord($id);
        $record->delete();

        return back()->with('success', 'Registro removido com sucesso.');
    }

    protected function model()
    {
        return new ($this->modelClass);
    }

    protected function findRecord($id)
    {
        return $this->model()->findOrFail($id);
    }

    protected function validated(\Illuminate\Http\Request $request, string $type)
    {
        $method = $type === 'store' ? 'validateStore' : 'validateUpdate';
        $rules = $this->{$method}();

        $validated = $request->validate($rules, [], $this->validationAttributeMessages());

        return $validated;
    }

    /**
     * Override nos controllers filhos para configurar.
     */
    protected function modelClass(): string
    {
        throw new \LogicException('Defina modelClass no controller filho.');
    }

    protected function searchable(): string
    {
        return '';
    }

    protected function applySearch($query, string $search): void
    {
        // implementar no filho
    }

    protected function filters(): array
    {
        return [];
    }

    protected function applyFilters($query): void
    {
        // implementar no filho
    }

    protected function validationAttributeMessages(): array
    {
        return [];
    }
}
