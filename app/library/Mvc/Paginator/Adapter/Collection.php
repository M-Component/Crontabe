<?php
namespace Mvc\Paginator\Adapter;
class Collection implements \Phalcon\Paginator\AdapterInterface
{

    private $model;

    private $fileds;

    private $conditions;

    private $sort;

    private $page;

    private $limit;

    private $skip;

    /**
     * @param array $options
     */
    public function __construct(array $options)
    {
        $builder =$options['builder'];
        $model_name = $builder['from'];
        $this->model = new $model_name;
        if (!($this->model instanceof \Phalcon\Mvc\Collection)) {
			throw new \Exception("Invalid Collection Model");
		}
        $this->columns = isset($builder['columns']) ? $builder['columns'] :[];
        $this->conditions = isset($builder['where']) ? $builder['where'] :[];
        $this->sort = $builder['orderBy'];
        if(!$options['page']){
            throw new \Exception("Invalid Page");
        }
        if(!$options['limit']){
            throw new \Exception("Invalid Limit");
        }

        $this->setLimit($options['limit']);
        $this->setCurrentPage($options['page']);

        $this->skip = ($options['page']-1) * $options['limit'];
    }

    public function setLimit($limit){
        $this->limit =$limit;
    }

    public function getLimit(){
        return $this->limit;
    }
    /**
     * @param int $page
     */
    public function setCurrentPage($page)
    {
        $this->page = $page;
    }
    /**
     * @return \stdClass
     */
    public function getPaginate()
    {
        $totalItems = $this->model->count(['conditions'=>$this->conditions]);
        $totalPages = intval(ceil($totalItems / $this->limit));
        $queryParams = [
            'conditions' =>$this->conditions,
            'fields' =>$this->columns,
            'limit' => $this->limit,
            'skip' => $this->skip
        ];
        if($this->sort){
            $queryParams['sort'] =$this->sort;
        }
        $items = $this->model->find($queryParams);
        $before =  $this->page - 1>0 ? $this->page-1 :1;
        $next = $this->page + 1 <$totalPages ? $this->page+1 :$totalPages;

        return (object) [
            'items' => $items,
            'current' => $this->page,
            'first' =>1,
            'before' => $before,
            'next' => $next,
            'last' => $totalPages,
            'total_pages' => $totalPages,
            'total_items' => $totalItems,
            'limit' =>$this->limit
        ];
    }
}
