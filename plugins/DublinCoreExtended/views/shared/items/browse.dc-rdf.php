<rdf:RDF xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:dcterms="http://purl.org/dc/terms/">
<?php
$itemDcRdf = new Output_ItemDcRdf;
foreach (loop('items') as $item) {
    echo $itemDcRdf->itemToDcRdf($item);
}
?>
</rdf:RDF>
