<?php
use Mvc\Paginator\Adapter\Collection as PaginatorCollectionBuilder;
class CollectionController extends BackstageController
{

    protected function getPageData($filter ,$model_name ,$page,$limit, $orderBy=''){
        $orderBy = $orderBy ?: 'create_time desc';
        $condition =\Mvc\CollectionFilter::filter($filter);
        $orderBy =\Mvc\CollectionFilter::sort($orderBy);

        $builder =array(
            'columns'=>[],
            'from'=>$model_name,
            'where'=>$condition,
            'orderBy'=>$orderBy,
        );
        $paginator = new PaginatorCollectionBuilder(
            [
                "builder" => $builder,
                "limit" => $limit,
                "page" => $page,
            ]
        );
        $page = $paginator->getPaginate();
        $items = $page->items;
        foreach($items as &$item){
            $item =$item->toArray();
        }
        $page->items =$items;
        return $page;
    }

}
