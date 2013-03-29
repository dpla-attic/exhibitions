use dpla_omeka;
--replace second argument with old server address, and last agrument with new server address
update omeka_files set original_filename = replace(original_filename, "http://54.245.164.12:3000/", "http://166.78.241.246/exhibitions/st/")
