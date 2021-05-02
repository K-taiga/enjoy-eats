up:
	docker-compose up -d 

down:
	docker-compose down

build:
	docker-compose build

# シェルに入る
app-sh:
	docker-compose exec php bash

# DB

# DB コンテナのシェルに入る
db-sh:
	docker-compose exec mariadb bash

# DBのドキュメントを更新する
doc-db-update:
	rm -rf ./document/dbdoc/*
	docker-compose up db-doc
