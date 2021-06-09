<?php

class Constants
{
    const DFS = 0;  // Depth First Search / Stack
    const BFS = 1;  // Breadth First Search / Queue
    const RDFS = 2; // Reversed DFS
}


class Tree
{
    public $nodes;
    private $root;
    private $method;
    private $queue;
    private $stack;
    private $visited;

    public function __construct($data = null) {
        $this->nodes = NULL;
        $this->root = NULL;
        $this->method = NULL;
        $this->queue = NULL;
        $this->stack = NULL;
        $this->visited = NULL;
        if ($data)
            $this->loadData($data);
        else
            echo "no data";
        $this->setMethod('DFS');
    }

    public function loadData($data) {
        $this->nodes = array();
        foreach($data as $node) {
            $this->nodes[$node['id']] = $node;
            if ($node['parent'] == 'NULL')
                $this->root = $node['id'];
        }
    }

    public function setMethod($method) {
        if ($method == 'DFS')
            $this->method = Constants.DFS;
        if ($method == 'BFS')
            $this->method = Constants.BFS;
        if ($method == 'RDFS')
            $this->method = Constants.RDFS;
        unset($this->queue);
        unset($this->stack);
        unset($this->visited);
        $this->queue = new SplQueue();
        $this->stack = new SplStack();
        $this->visited = array();
    }

    public function start() {
        if ($this->method == Constants.DFS)
            $this->stack->push($this->root);
        if ($this->method == Constants.BFS)
            $this->queue->enqueue($this->root);
        if ($this->method == Constants.RDFS)
            $this->stack->push($this->root);
        return $this->nodes[$this->root];
    }

    public function next($callback = null) {
        if ($this->method == Constants.DFS) {
            if (!$this->stack->isEmpty()) {
                $id = $this->stack->pop();
                $node = $this->nodes[$id];
                if ($callback) $callback($node);
                if ($node['child']) {
                    $childs = explode(',', $node['child']);
                    foreach (array_reverse($childs) as $key)
                        $this->stack->push($key);
                }
                return $node;
            } else return null;
        }

        if ($this->method == Constants.BFS) {
            if (!$this->queue->isEmpty()) {
                $id = $this->queue->dequeue();
                $node = $this->nodes[$id];
                if ($callback) $callback($node);
                if ($node['child']) {
                    $childs = explode(',', $node['child']);
                    foreach ($childs as $key)
                        $this->queue->enqueue($key);
                }
                return $node;
            } else return null;
        }

        if ($this->method == Constants.RDFS) {
            if (!$this->stack->isEmpty()) {
                $id = $this->stack->pop();
                $node = $this->nodes[$id];
                $childs = null;
                if ($node['child']) {
                    $childs = explode(',', $node['child']);
                    foreach ($childs as $i => $key) {
                        if ($this->visited[$key])
                            unset($childs[$i]);
                    }
                }
                if ($childs) {
                    $this->stack->push($id);
                    foreach (array_reverse($childs) as $key)
                        $this->stack->push($key);
                    return 'skipped';
                } else {
                    if ($callback) $callback($node);
                    $this->visited[$id] = 1;
                    return $node;
                }
            } else return null;
        }
    }
}

?>