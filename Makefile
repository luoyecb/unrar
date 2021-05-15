#
.PHONY: default env build clean

default: build

env:
	@echo === php version ===
	@php -v
	@echo

clean:
	rm -rf *.phar

build: clean
	@php scripts/build_phar.php -dir="./" -execMode -index unrar.php -output unrar.phar
	@echo Build successfully.
