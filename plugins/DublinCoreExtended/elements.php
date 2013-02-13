<?php
/**
 * Dublin Core Extended
 * 
 * @copyright Copyright 2007-2012 Roy Rosenzweig Center for History and New Media
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GNU GPLv3
 */

// Dublin Core properties and property refinements. See DCMI Metadata Terms: 
// http://dublincore.org/documents/dcmi-terms/
$elements = array(
    array(
        'label' => 'Title', 
        'name'  => 'title'
    ), 
    array(
        'label'       => 'Alternative Title', 
        'name'        => 'alternative', 
        'description' => 'An alternative name for the resource. The distinction between titles and alternative titles is application-specific.', 
        '_refines'    => 'Title'
    ), 
    array(
        'label' => 'Subject', 
        'name'  => 'subject'
    ), 
    array(
        'label' => 'Description', 
        'name'  => 'description'
    ), 
    array(
        'label'       => 'Abstract', 
        'name'        => 'abstract', 
        'description' => 'A summary of the resource.', 
        '_refines'    => 'Description'
    ), 
    array(
        'label'       => 'Table Of Contents', 
        'name'        => 'tableOfContents', 
        'description' => 'A list of subunits of the resource.', 
        '_refines'    => 'Description'
    ), 
    array(
        'label' => 'Creator', 
        'name'  => 'creator'
    ), 
    array(
        'label' => 'Source', 
        'name'  => 'source'
    ), 
    array(
        'label' => 'Publisher', 
        'name'  => 'publisher'
    ), 
    array(
        'label' => 'Date', 
        'name'  => 'date'
    ), 
    array(
        'label'       => 'Date Available', 
        'name'        => 'available', 
        'description' => 'Date (often a range) that the resource became or will become available.', 
        '_refines'    => 'Date'
    ), 
    array(
        'label'       => 'Date Created', 
        'name'        => 'created', 
        'description' => 'Date of creation of the resource.', 
        '_refines'    => 'Date'
    ), 
    array(
        'label'       => 'Date Accepted', 
        'name'        => 'dateAccepted', 
        'description' => 'Date of acceptance of the resource. Examples of resources to which a Date Accepted may be relevant are a thesis (accepted by a university department) or an article (accepted by a journal).', 
        '_refines'    => 'Date'
    ), 
    array(
        'label'       => 'Date Copyrighted', 
        'name'        => 'dateCopyrighted', 
        'description' => 'Date of copyright.', 
        '_refines'    => 'Date'
    ), 
    array(
        'label'       => 'Date Submitted', 
        'name'        => 'dateSubmitted', 
        'description' => 'Date of submission of the resource. Examples of resources to which a Date Submitted may be relevant are a thesis (submitted to a university department) or an article (submitted to a journal).', 
        '_refines'    => 'Date'
    ), 
    array(
        'label'       => 'Date Issued', 
        'name'        => 'issued', 
        'description' => 'Date of formal issuance (e.g., publication) of the resource.', 
        '_refines'    => 'Date'
    ), 
    array(
        'label'        => 'Date Modified', 
        'name'        => '', 
        'description' => 'Date on which the resource was changed.', 
        '_refines'    => 'Date'
    ), 
    array(
        'label'       => 'Date Valid', 
        'name'        => 'valid', 
        'description' => 'Date (often a range) of validity of a resource.', 
        '_refines'    => 'Date'
    ), 
    array(
        'label' => 'Contributor', 
        'name'  => 'contributor'
    ), 
    array(
        'label' => 'Rights', 
        'name'  => 'rights'
    ), 
    array(
        'label'       => 'Access Rights', 
        'name'        => 'accessRights', 
        'description' => 'Information about who can access the resource or an indication of its security status. Access Rights may include information regarding access or restrictions based on privacy, security, or other policies.', 
        '_refines'    => 'Rights'
    ), 
    array(
        'label'       => 'License', 
        'name'        => 'license', 
        'description' => 'A legal document giving official permission to do something with the resource.', 
        '_refines'    => 'Rights'
    ), 
    array(
        'label' => 'Relation', 
        'name'  => 'relation'
    ), 
    array(
        'label'       => 'Conforms To', 
        'name'        => 'conformsTo', 
        'description' => 'An established standard to which the described resource conforms.', 
        '_refines'    => 'Relation'
    ), 
    array(
        'label'       => 'Has Format', 
        'name'        => 'hasFormat', 
        'description' => 'A related resource that is substantially the same as the pre-existing described resource, but in another format.', 
        '_refines'    => 'Relation'
    ), 
    array(
        'label'       => 'Has Part', 
        'name'        => 'hasPart', 
        'description' => 'A related resource that is included either physically or logically in the described resource.', 
        '_refines'    => 'Relation'
    ), 
    array(
        'label'       => 'Has Version', 
        'name'        => 'hasVersion', 
        'description' => 'A related resource that is a version, edition, or adaptation of the described resource.', 
        '_refines'    => 'Relation'
    ), 
    array(
        'label'       => 'Is Format Of', 
        'name'        => 'isFormatOf', 
        'description' => 'A related resource that is substantially the same as the described resource, but in another format.', 
        '_refines'    => 'Relation'
    ), 
    array(
        'label'       => 'Is Part Of', 
        'name'        => 'isPartOf', 
        'description' => 'A related resource in which the described resource is physically or logically included.', 
        '_refines'    => 'Relation'
    ), 
    array(
        'label'       => 'Is Referenced By', 
        'name'        => 'isReferencedBy', 
        'description' => 'A related resource that references, cites, or otherwise points to the described resource.', 
        '_refines'    => 'Relation'
    ), 
    array(
        'label'       => 'Is Replaced By', 
        'name'        => 'isReplacedBy', 
        'description' => 'A related resource that supplants, displaces, or supersedes the described resource.', 
        '_refines'    => 'Relation'
    ), 
    array(
        'label'       => 'Is Required By', 
        'name'        => 'isRequiredBy', 
        'description' => 'A related resource that requires the described resource to support its function, delivery, or coherence.', 
        '_refines'    => 'Relation'
    ), 
    array(
        'label'       => 'Is Version Of', 
        'name'        => 'isVersionOf', 
        'description' => 'A related resource of which the described resource is a version, edition, or adaptation. Changes in version imply substantive changes in content rather than differences in format.', 
        '_refines'    => 'Relation'
    ), 
    array(
        'label'       => 'References', 
        'name'        => 'references', 
        'description' => 'A related resource that is referenced, cited, or otherwise pointed to by the described resource.', 
        '_refines'    => 'Relation'
    ), 
    array(
        'label'       => 'Replaces', 
        'name'        => 'replaces', 
        'description' => 'A related resource that is supplanted, displaced, or superseded by the described resource.', 
        '_refines'    => 'Relation'
    ), 
    array(
        'label'       => 'Requires', 
        'name'        => 'requires', 
        'description' => 'A related resource that is required by the described resource to support its function, delivery, or coherence.', 
        '_refines'    => 'Relation'
    ), 
    array(
        'label' => 'Format', 
        'name'  => 'format'
    ), 
    array(
        'label'       => 'Extent', 
        'name'        => 'extent', 
        'description' => 'The size or duration of the resource.', 
        '_refines'    => 'Format'
    ), 
    array(
        'label'       => 'Medium', 
        'name'        => 'medium', 
        'description' => 'The material or physical carrier of the resource.', 
        '_refines'    => 'Format'
    ), 
    array(
        'label' => 'Language', 
        'name'  => 'language'
    ), 
    array(
        'label' => 'Type', 
        'name'  => 'type'
    ), 
    array(
        'label' => 'Identifier', 
        'name'  => 'identifier'
    ), 
    array(
        'label'       => 'Bibliographic Citation', 
        'name'        => 'bibliographicCitation', 
        'description' => 'A bibliographic reference for the resource. Recommended practice is to include sufficient bibliographic detail to identify the resource as unambiguously as possible.', 
        '_refines'    => 'Identifier'
    ), 
    array(
        'label' => 'Coverage', 
        'name'  => 'coverage'
    ), 
    array(
        'label'       => 'Spatial Coverage', 
        'name'        => 'spatial', 
        'description' => 'Spatial characteristics of the resource.', 
        '_refines'    => 'Coverage'
    ), 
    array(
        'label'       => 'Temporal Coverage', 
        'name'        => 'temporal', 
        'description' => 'Temporal characteristics of the resource.', 
        '_refines'    => 'Coverage'
    ), 
    array(
        'label'       => 'Accrual Method', 
        'name'        => 'accrualMethod', 
        'description' => 'The method by which items are added to a collection.', 
    ), 
    array(
        'label'       => 'Accrual Periodicity', 
        'name'        => 'accrualPeriodicity', 
        'description' => 'The frequency with which items are added to a collection.', 
    ), 
    array(
        'label'       => 'Accrual Policy', 
        'name'        => 'accrualPolicy', 
        'description' => 'The policy governing the addition of items to a collection.', 
    ), 
    array(
        'label'       => 'Audience', 
        'name'        => 'audience', 
        'description' => 'A class of entity for whom the resource is intended or useful.', 
    ), 
    array(
        'label'       => 'Audience Education Level', 
        'name'        => 'educationLevel', 
        'description' => 'A class of entity, defined in terms of progression through an educational or training context, for which the described resource is intended.', 
        '_refines'    => 'Audience'
    ), 
    array(
        'label'       => 'Mediator', 
        'name'        => 'mediator', 
        'description' => 'An entity that mediates access to the resource and for whom the resource is intended or useful. In an educational context, a mediator might be a parent, teacher, teaching assistant, or care-giver.', 
        '_refines'    => 'Audience'
    ), 
    array(
        'label'       => 'Instructional Method', 
        'name'        => 'instructionalMethod', 
        'description' => 'A process, used to engender knowledge, attitudes and skills, that the described resource is designed to support. Instructional Method will typically include ways of presenting instructional materials or conducting instructional activities, patterns of learner-to-learner and learner-to-instructor interactions, and mechanisms by which group and individual levels of learning are measured. Instructional methods include all aspects of the instruction and learning processes from planning and implementation through evaluation and feedback.', 
    ), 
    array(
        'label'       => 'Provenance', 
        'name'        => 'provenance', 
        'description' => 'A statement of any changes in ownership and custody of the resource since its creation that are significant for its authenticity, integrity, and interpretation. The statement may include a description of any changes successive custodians made to the resource.', 
    ), 
    array(
        'label'       => 'Rights Holder', 
        'name'        => 'rightsHolder', 
        'description' => 'A person or organization owning or managing rights over the resource.', 
    )
);
