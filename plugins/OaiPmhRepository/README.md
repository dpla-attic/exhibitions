# OAI-PMH Repository #
A plugin for Omeka.

This plugin implements an Open Archives Initiative Protocol for Metadata
Harvesting ([OAI-PMH][1]) repository for Omeka, allowing Omeka items to be
harvested by OAI-PMH harvesters. The plugin implements version 2.0 of the
protocol.

## Metadata Formats ##

The plugin ships with several default formats. Other plugins can alter these or add their own (see [Extending](#extending)).

### Dublin Core (`oai_dc`) ###

This is required by the OAI-PMH specification for all repositories. Omeka metadata fields are mapped one-to-one with
fields for this output format.

### CDWA Lite (`cdwalite`) ###

The mapping between Omeka's metadata and CDWA Lite metadata is more complicated, and certain fields may not be populated correctly.
The chief advantage of using CDWA Lite output is that file URLs can be output in a controlled format, unlike Dublin Core. Harvesters
may therefore be able to harvest or link to files in addition to metadata.

### MODS (`mods`) ###

This output crosswalks the Dublin Core metadata to MODS using the mapping recommended by the Library of Congress.

### METS (`mets`) ###

The Metadata Encoding and Transmission Standard format exposes files to harvesters alongside Dublin Core metadata.

### RDF (`rdf`) ###

This format exposes metadata as RDF/XML. Unlike many of the other formats, RDF allows the repository to expose metadata from different
standards all in the same output. The main practical distinction from other formats currently is that the RDF output will 
automatically include "qualified" data from the Dublin Core Extended plugin, if it's present. 

### Omeka XML (`omeka-xml`) ###

This output format uses an Omeka-specific XML output that includes all metadata elements without requiring crosswalking or subsetting,
but is not well-supported by harvesters or other tools.

## Configuration ##

The plugin has several user-configurable values. You will be prompted to set these at installation time, or you can change them at any time from the Configure link on the plugin management page.

### Repository name ###
Name for this OAI-PMH repository. This value is sent as part of the response to an Identify request, and it is how the repository
will be identified by well-behaved harvesters.

Default: The name of the Omeka installation.

### Namespace identifier ###
The oai-identifier specification requires repositories to specify a namespace identifier. This will be used to form globally unique
IDs for the exposed metadata items. This value is required to be a domain name you have registered. Using other values will generate
invalid identifiers.

Default: If it can, the plugin will try to automatically detect the domain of the server hosting the site, and use that as the 
default namespace identifier. If a name can't be detected (for example, if the site is accessed through the `localhost` domain), the 
default will be "default.must.change" (as you might think, this value is intended to be changed, not used as-is).  The plugin will
function with this, or any other string, as the namespace identifier, but this breaks the assumption that each identifier is globally
unique. Best practice is to set this value to the domain name the Omeka server is published at, possibly with a prefix like "oai."

### Expose files ###
Whether the repository should expose direct URLs to all the files associated with an item as part of its returned metadata. This gives
harvesters the ability to directly access the files described by the metadata.

Default: true

### Expose empty collections ###
Whether the plugin should expose empty public collections. If enabled, all public collections are included in ListSets output. If
disabled, only collections that actually contain at least one public item will be included in the ListSets output.

Default: true

*Added in version 2.1*

### Expose item type ###
Whether the plugin should expose the item type as Dublin Core Type. When enabled, for items that belong to an item type, the
repository will expose an additional Dublin Core Type element with a value of the item type's name. Note that this option will only
expose the item type *name*, not any other item type metadata.

Default: false

*Added in version 2.1*

## Advanced Configuration ##
The plugin also allows you to configure some more options about how the repository responds to harvesters. Since the default values
are recommended for most users, these values must be edited by hand, in the config.ini file in the plugin's root directory.

### List response limit ###
Number of individual items that can be returned in a response at once. Larger values will increase memory usage but reduce the number 
of database queries and HTTP requests. Smaller values will reduce memory usage but increase the number of DB queries and requests.

Default: 50

### List expiration time ###
Amount of time in minutes a resumptionToken is valid for. The specification suggests a number in the tens of minutes. This boils down 
to the length of time a harvester has to request the next part of an incomplete list request.

Default: 10 (minutes)

## Extending ##

The plugin provides a filter that other plugins can use to add new metadata formats or replace the existing ones with new
implementations. As of version 2.1, it's no longer necessary to add or change files within the plugin itself to change the
available formats.

### Filter `oai_pmh_repository_metadata_formats` 

The filte passes no extra parameters. The value being filtered is an array
of arrays, where each inner array describes a single metadata format. The key in the outer array is the metadata prefix for the
format (i.e., `oai_dc` or `rdf`). Each inner array has three mandatory keys:

* `class` is the name of a class implementing `OaiPmhRepository_Metadata_FormatInterface`. This class holds the actual implementation
  of the format.
* `namespace` is the XML namespace for the format.
* `schema` is the location of the XML schema for the format.

## Version History ##

*2.1.1*

* Fixed a bug with XML special characters in Collection names
* New translations: Catalan, Czech, German, Mongolian, Polish, Portuguese (Brazil), Portuguese (Portugal), Serbian

*2.1*

* New RDF metadata format
* New `oai_pmh_repository_metadata_formats` filter to allow other plugins to add and modify metadata formats
* Localization support (contributed by [jajm](https://github.com/jajm))
* New option to exclude empty collections from ListSets (contributed by [Daniel-KM](https://github.com/Daniel-KM))
* New option to expose item type as Dublin Core Type value (contributed by [Daniel-KM](https://github.com/Daniel-KM))
* More accurate "earliest datestamp" calculation (contributed by [Daniel-KM](https://github.com/Daniel-KM))
* Fixed "expose files" flag check for METS and omeka-xml outputs (contributed by [Daniel-KM](https://github.com/Daniel-KM))
* Additional miscellaneous cleanup (significant portions contributed by [Daniel-KM](https://github.com/Daniel-KM))

*2.0*

* Initial support for Omeka 2.0 and up
* File exposure support for METS

 [1]: https://www.openarchives.org/OAI/openarchivesprotocol.html
