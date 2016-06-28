# sped-serializer

Apenas prova de conceito

A classe XmlParser::class permite basicamente duas operações

1 - Método xmlToObj()
Converte um XML em um Objeto do tipo StdClass do PHP para ser usado para extrair os dados do xml em programas PHP de forma mais simples e direta, ao invés de usar o DOM par essa operação.

2 - Método objToXml()
Converte um objeto do tipo StdClass em um XML.
Caso o objeto não contenha o namespace ("xmlns") relativo aos do projeto SPED, estes serão inclusos.
Caso o objeto já contenha os namespaces esses serão inclusos automaticamente.



