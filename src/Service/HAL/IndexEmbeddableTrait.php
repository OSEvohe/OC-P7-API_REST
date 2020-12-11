<?php


namespace App\Service\HAL;


use App\Dto\IndexDto;
use Exception;
use Symfony\Component\HttpFoundation\RequestStack;

trait IndexEmbeddableTrait
{
    /** @var IndexDto */
    protected $indexDto;

    /** @var array */
    protected $entityList;


    /**
     * @param RequestStack $requestStack
     * @Required
     */
    public function setRequest(RequestStack $requestStack){
        $this->request = $requestStack->getCurrentRequest();
    }


    public function getEntityListHAL(array $data, string $embeddedFieldName = 'results'): IndexDto
    {
        if (!isset($data['list']) || !isset($data['count'])){
            throw new Exception('Missing field(s) in array passed to getEntityListHAL, did you forgot to set list or count fields?');
        }

        $this->indexDto = new IndexDto(null);
        $this->entityList = $data['list'];

        $this->setIndexPagination($data['count']);
        $this->setIndexLinks($data['count']);
        $this->setIndexEmbedded($this, $embeddedFieldName);

        return $this->indexDto;
    }


    protected function setIndexPagination($totalCount)
    {
        $page = $this->request->get('_route_params')['page'];
        $limit = $this->request->get('_route_params')['limit'];
        if ($totalCount > $limit){
            $totalPages = (floor($totalCount / $limit)+1);
        } else {
            $totalPages = 1;
        }

        $this->indexDto->setPage([
            'size' => count($this->entityList),
            'totalElements' => $totalCount,
            'totalPages' => $totalPages,
            'number' => $page
        ]);
    }


    protected function setIndexLinks($count)
    {
        $route = $this->request->get('_route');
        $page = $this->request->get('_route_params')['page'];
        $limit = $this->request->get('_route_params')['limit'];

        $this->indexDto->addLink('first', ['href' => $this->router->generate($route, ['page' => 1, 'limit' => $limit])]);

        if ($page > 1) {
            $this->indexDto->addLink('prev', ['href' => $this->router->generate($route, ['page' => $page - 1, 'limit' => $limit])]);
        }

        $this->indexDto->addLink('self', ['href' => $this->request->getPathInfo()]);

        if ($count > $page * $limit) {
            $this->indexDto->addLink('next', ['href' => $this->router->generate($route, ['page' => $page + 1, 'limit' => $limit])]);
        }

        if ($count > $limit) {
            $lastPage = (floor($count / $limit)+1);
            $this->indexDto->addLink('last', ['href' => $this->router->generate($route, ['page' => $lastPage, 'limit' => $limit])]);
        }
    }


    protected function setIndexEmbedded($embeddedHAL, $fieldName = 'results')
    {
        $results = [];
        foreach ($this->entityList as $entity){
            $results[] = $embeddedHAL->getHAL($entity);
        }
        $this->indexDto->addEmbedded($fieldName, $results);
    }
}