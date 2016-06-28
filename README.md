# sped-serializer

## Apenas prova de conceito, por ora.

[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Latest Version on Packagist][ico-version]][link-packagist]
[![License][ico-license]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]


**A classe XmlParser::class permite basicamente duas operações:**

1 - Método estático XmlParser::xmlToObj()

Converte um XML em um Objeto do tipo StdClass do PHP para ser usado para extrair os dados do xml em programas PHP de forma mais simples e direta, ao invés de usar o DOM para essa operação.

2 - Método estático XmlParser::objToXml()

Converte um objeto do tipo StdClass em um XML.

>Caso o objeto não contenha o namespace ("xmlns") relativo aos do projeto SPED, estes serão inclusos.

>Caso o objeto já contenha os namespaces esses serão inclusos automaticamente.


[ico-stars]: https://img.shields.io/github/stars/nfephp-org/sped-serializer.svg?style=flat-square
[ico-forks]: https://img.shields.io/github/forks/nfephp-org/sped-serializer.svg?style=flat-square
[ico-issues]: https://img.shields.io/github/issues/nfephp-org/sped-serializer.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/nfephp-org/sped-serializer/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/nfephp-org/sped-serializer.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/nfephp-org/sped-serializer.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/nfephp-org/sped-serializer.svg?style=flat-square
[ico-version]: https://img.shields.io/packagist/v/nfephp-org/sped-serializer.svg?style=flat-square
[ico-license]: https://poser.pugx.org/nfephp-org/nfephp/license.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/nfephp-org/sped-serializer
[link-travis]: https://travis-ci.org/nfephp-org/sped-serializer
[link-scrutinizer]: https://scrutinizer-ci.com/g/nfephp-org/sped-serializer/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/nfephp-org/sped-serializer
[link-downloads]: https://packagist.org/packages/nfephp-org/sped-serializer
[link-author]: https://github.com/nfephp-org
[link-issues]: https://github.com/nfephp-org/sped-serializer/issues
[link-forks]: https://github.com/nfephp-org/sped-serializer/network
[link-stars]: https://github.com/nfephp-org/sped-serializer/stargazers
