# -*- coding: utf-8 -*-
import tree
import pymysql


def my_callback(node):
    print("[{0:<7}]".format(node['id']), end=' ')
    print("[{0:<8}]".format(node['tag']), end=' ')
    print(node['text'])


if __name__ == "__main__":
    info = {'host': 'dshareai.cafe24.com', 'port': 3306, 'user': 'dshareai', 'passwd': 'adotAI!@34', 'db': 'dshareai'}
    conn = pymysql.connect(host=info['host'], port=info['port'], user=info['user'], passwd=info['passwd'], db=info['db'], charset='utf8', autocommit=True, cursorclass=pymysql.cursors.DictCursor)
    cursor = conn.cursor()
    cursor.execute("SELECT * FROM `SentenceTrees` WHERE `sentence_id`='11'")
    data = cursor.fetchall()
    for i in range(len(data)):
        for key, value in data[i].items():
            if type(value) == bytes:
                data[i][key] = value.decode("utf-8")
    print(data)

    tree = tree.Tree(data)
    # print(tree.nodes)
    # print(tree.nodes['j1_1'])

    print("\nDFS>>>")
    tree.setMethod('DFS')
    tree.start()
    while True:
        if tree.next(callback=my_callback) == None:
            break

    print("\nBFS>>>")
    tree.setMethod('BFS')
    tree.start()
    while True:
        if tree.next(callback=my_callback) == None:
            break

    print("\nRDFS>>>")
    tree.setMethod('RDFS')
    tree.start()
    while True:
        if tree.next(callback=my_callback) == None:
            break

    exit()
