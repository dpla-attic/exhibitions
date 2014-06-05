Exhibitions
===========

DPLA Exhibitions by Omeka
v15.0 (2014-06-05)

Documentation
-------------

* Configuration:
	* Review the "dpla" section of `application/config/config.ini`.
	* You will need to set the `dpla.apiKey` option to a valid DPLA API key.
* Additional documentation:
	* [DPLA Digital Exhibition Creation Guidelines](https://docs.google.com/document/d/1caBYKDdQCpFCildS5XquNML5YzaugSL7Jf3CdBIIqOA/edit)
	* [Creating Exhibitions with the DPLA Template](https://docs.google.com/document/d/1ktQuLJzMkQX_e5EM2cUm88W614GZ4UdRhuu3_cXpoXU/edit)

License
--------
This application is released under an AGPLv3 license.

Copyright President and Fellows of Harvard College, 2013
Copyright Digital Public Library of America, 2014

Branching
---------

Our workflow is a simplified version of the
[git-flow](http://nvie.com/posts/a-successful-git-branching-model/) workflow.

We use `master`, `develop`, and topic branches.  `develop` is branched to a
release branch, which is then merged into `master`.  Merges of topic branches
into `develop`, are done with git's `--squash` option.

If you have any questions about contributing, please contact
dev [at the domain] dp.la.
