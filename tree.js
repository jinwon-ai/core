const DFS = 0;    // Depth First Search / Stack
const BFS = 1;    // Breadth First Search / Queue
const RDFS = 2;   // Reversed DFS

class Queue {
    constructor() { this._arr = []; }
    enqueue(item) { this._arr.push(item); }
    dequeue() { return this._arr.shift(); }
    empty() { return (this._arr.length == 0)? true:false; }
}

class Stack {
    constructor() { this._arr = []; }
    push(item) { this._arr.push(item); }
    pop() { return this._arr.pop(); }
    empty() { return (this._arr.length == 0)? true:false; }
}

class Utils {
    static pad(n, width, c) {
        n = n + '';
        return n.length >= width ? n : n + new Array(width - n.length + 1).join(c);
    }
}

class Tree {
    constructor(data=null) {
        this.nodes = null;
        this.root = null;
        this.method = null
        this.queue = null;
        this.stack = null;
        this.visited = null;
        if (data) {
            this.loadData(data);
        }
        this.setMethod('DFS');
    }

    loadData(data) {
        this.nodes = {};
        for (var i in data) {
            var key = data[i]['id'];
            this.nodes[key] = data[i];
            if (data[i]['parent'] == 'NULL')
                this.root = key;
        }
    }

    setMethod(method) {
        if (method == 'DFS')
            this.method = DFS;
        if (method == 'BFS')
            this.method = BFS;
        if (method == 'RDFS')
            this.method = RDFS;
        delete this.queue;
        delete this.stack;
        delete this.visited;
        this.queue = new Queue();
        this.stack = new Stack();
        this.visited = {};
    }

    start() {
        if (this.method == DFS)
            this.stack.push(this.root);
        if (this.method == BFS)
            this.queue.enqueue(this.root);
        if (this.method == RDFS)
            this.stack.push(this.root);
        return this.nodes[this.root];
    }

    next(callback=null) {
        if (this.method == DFS) {
            if (!this.stack.empty()) {
                var id = this.stack.pop();
                var node = this.nodes[id];
                if (callback) callback(node);
                if (node['child']) {
                    var childs = node['child'].split(',');
                    for (var i = childs.length - 1; i >= 0; i--)
                        this.stack.push(childs[i]);
                }
                return node;
            } else return null;
        }

        if (this.method == BFS) {
            if (!this.queue.empty()) {
                var id = this.queue.dequeue();
                var node = this.nodes[id];
                if (callback) callback(node);
                if (node['child']) {
                    var childs = node['child'].split(',');
                    for (var i = 0; i < childs.length; i++)
                        this.queue.enqueue(childs[i]);
                }
                return node;
            } else return null;
        }

        if (this.method == RDFS) {
            if (!this.stack.empty()) {
                var id = this.stack.pop();
                var node = this.nodes[id];
                var childs = new Array();
                if (node['child']) {
                    childs = node['child'].split(',');
                    for (var i = childs.length - 1; i >= 0; i--)
                        if (this.visited[childs[i]])
                            childs = childs.filter(function(value, index, arr){ return value != childs[i]; });
                }
                if (childs.length > 0) {
                    this.stack.push(id);
                    for (var i = childs.length - 1; i >= 0; i--)
                        this.stack.push(childs[i]);
                    return 'skipped';
                } else {
                    if (callback) callback(node);
                    this.visited[id] = 1;
                    return node;
                }
            } else return null;
        }
    }
}