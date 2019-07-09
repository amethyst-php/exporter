<?php

namespace Amethyst\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Amethyst\Api\Http\Controllers\RestManagerController;
use Amethyst\Api\Http\Controllers\Traits as RestTraits;
use Amethyst\Managers\ExporterManager;

class ExportersController extends RestManagerController
{
    use RestTraits\RestIndexTrait;
    use RestTraits\RestShowTrait;
    use RestTraits\RestCreateTrait;
    use RestTraits\RestUpdateTrait;
    use RestTraits\RestRemoveTrait;

    /**
     * The class of the manager.
     *
     * @var string
     */
    public $class = ExporterManager::class;

    /**
     * Execute.
     *
     * @param int                      $id
     * @param \Illuminate\Http\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function execute(int $id, Request $request)
    {
        /** @var \Amethyst\Managers\ExporterManager */
        $manager = $this->manager;

        /** @var \Amethyst\Models\Exporter */
        $exporter = $manager->getRepository()->findOneById($id);

        if ($exporter == null) {
            return $this->not_found();
        }

        $result = $manager->generate($exporter, (array) $request->input('data'));

        if (!$result->ok()) {
            return $this->error(['errors' => $result->getSimpleErrors()]);
        }

        return $this->success([]);
    }
}
