# -*- coding: utf-8 -*-
import constant
from collections import deque


class Tree:
    def __init__(self, data=None):
        self.nodes = None
        self.root = None
        self.method = constant.DFS
        self.queue = deque()
        self.stack = deque()
        self.visited = {}
        if data:
            self.loadData(data)
        self.setMethod('DFS');
        return

    def loadData(self, data):
        self.nodes = {}
        for node in data:
            key = node['id']
            self.nodes[key] = node
            if node['parent'] == 'NULL':
                self.root = key

    def setMethod(self, method):
        if method == 'DFS':
            self.method = constant.DFS
        if method == 'BFS':
            self.method = constant.BFS
        if method == 'RDFS':
            self.method = constant.RDFS
        self.queue.clear()
        self.stack.clear()
        self.visited = {}

    def start(self):
        if self.method == constant.DFS:
            self.stack.append(self.root)
        if self.method == constant.BFS:
            self.queue.append(self.root)
        if self.method == constant.RDFS:
            self.stack.append(self.root)
        return self.nodes[self.root]

    def next(self, callback=None):
        if self.method == constant.DFS:
            if self.stack:
                id = self.stack.pop()
                node = self.nodes[id]
                if callback:
                    callback(node)
                if node['child']:
                    childs = node['child'].split(",")
                    for key in reversed(childs):
                        self.stack.append(key)
                return node
            else:
                return None

        if self.method == constant.BFS:
            if self.queue:
                id = self.queue.popleft()
                node = self.nodes[id]
                if callback:
                    callback(node)
                if node['child']:
                    childs = node['child'].split(",")
                    for key in childs:
                        self.queue.append(key)
                return node
            else:
                return None

        if self.method == constant.RDFS:
            if self.stack:
                id = self.stack.pop()
                node = self.nodes[id]
                childs = None
                if node['child']:
                    childs = node['child'].split(",")
                    for key in reversed(childs):
                        if key in self.visited:
                            childs.remove(key)
                if childs:
                    self.stack.append(id)
                    for key in reversed(childs):
                        self.stack.append(key)
                    return 'skipped'
                else:
                    if callback:
                        callback(node)
                    self.visited[id] = 1
                    return node
            else:
                return None

