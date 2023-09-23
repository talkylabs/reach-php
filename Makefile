# Reachh API helper library.
# See LICENSE file for copyright and license details.
.PHONY: all clean install test test-docker docs authors docker-dev-build docker-dev-clean docker-dev-test

COMPOSER = $(shell which composer)
ifeq ($(strip $(COMPOSER)),)
	COMPOSER = php composer.phar
endif
PHPVERSION = $(shell php -r 'echo PHP_VERSION;')

all: test

clean:
	@rm -rf docs venv vendor composer.lock

install: clean
	@composer --version || (curl -s https://getcomposer.org/installer | php);
	$(COMPOSER) install

vendor: install

test:
	@PATH=vendor/bin:$(PATH) phpunit -d memory_limit=512M --strict-coverage --disallow-test-output --colors --configuration tests/phpunit.xml --coverage-clover=coverage.xml

test-docker:
	docker build -t talkylabs/reach-php .
	docker run talkylabs/reach-php phpunit -d memory_limit=512M --disallow-test-output --colors --configuration tests/phpunit.xml

PHPDOX_DIR=vendor-theseer
docs-install:
	COMPOSER_VENDOR_DIR=${PHPDOX_DIR} composer require --dev theseer/phpdox:\^0.12.0
	composer remove --dev theseer/phpdox

docs:
	${PHPDOX_DIR}/bin/phpdox

authors:
	echo "Authors\n=======\n\nA huge thanks to all of our contributors:\n\n" > AUTHORS.md
	git log --raw | grep "^Author: " | cut -d ' ' -f2- | cut -d '<' -f1 | sed 's/^/- /' | sort | uniq >> AUTHORS.md

API_DEFINITIONS_SHA=$(shell git log --oneline | grep Regenerated | head -n1 | cut -d ' ' -f 5)
CURRENT_TAG=$(shell expr "${GITHUB_TAG}" : ".*-rc.*" >/dev/null && echo "rc" || echo "latest")
docker-build:
	docker build -t talkylabs/reach-php .
	docker tag talkylabs/reach-php talkylabs/reach-php:${GITHUB_TAG}
	docker tag talkylabs/reach-php talkylabs/reach-php:apidefs-${API_DEFINITIONS_SHA}
	docker tag talkylabs/reach-php talkylabs/reach-php:${CURRENT_TAG}

docker-push:
	docker push talkylabs/reach-php:${GITHUB_TAG}
	docker push talkylabs/reach-php:apidefs-${API_DEFINITIONS_SHA}
	docker push talkylabs/reach-php:${CURRENT_TAG}

docker-dev-build:
	-docker stop reach_php${VERSION}
	-docker rm reach_php${VERSION}
	docker image build --tag="talkylabs/php${VERSION}" --build-arg version=${VERSION} -f ./Dockerfile-dev .
	docker run -td --name="reach_php${VERSION}" --mount type=bind,source=${PWD},target=/talkylabs talkylabs/php${VERSION} /bin/bash

docker-dev-clean:
	docker ps --format '{{.Names}}' | grep "^reach_php" | xargs -I {} sh -c "docker stop {} && docker rm {}" > /dev/null

docker-dev-test:
	docker exec -t reach_php${VERSION} /bin/bash -c 'make all'

cluster-test:
	@PATH=vendor/bin:$(PATH) phpunit --filter ClusterTest  tests/Reach/ClusterTest.php