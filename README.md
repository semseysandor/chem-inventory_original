README

LelTar

This is a chemical inventory suitable for a medium-sized company.
A very similar system is actually used in the industry.

Like any inventory, this system is designed to answer two questions:
1) WHAT we have
2) WHERE is it.

Chemicals are structured in categories/subcategories. There are compounds,
a compound can have batches and a batch can have many packs.
Locationwise you can design laboratories (rooms), inside laboratories different
storages (like cabinets, cupboards, fridges, etc.) and inside storages some sub-
storages (e.g. shelves of a cupboard). Anyway, you can use any logic, nevertheless
there is a 3-tier location system.

Compound, batch and pack management is through the web interface.
Locations & manufacturers are stored in the database as well as user right levels.

Barcodes are assigned to each pack, so you can use labels for identification
in the lab. (barcode printing is not implemented yet in this application)

Uploading the CoA and MSDS documents are possible for each batch.

Adding and editing batches and packs is only allowed for admins.
Users can add/edit compounds and upload documents, whereas guests only can view
and search the database.

FEATURES

- LDAP authentication
- Three user access levels (guest/user/admin)
- Add/edit compounds, batches and packs
- Inactivate packs when getting empty
- Changelog for audit trails
- Search with autocomplete
- Generate barcodes
- Drawing chemical structures

INSTALLATION

1) Install a web server (like Apache) with support for PHP version 5.5.9
2) Install a LDAP server
2) Copy all the source files to host on your server
3) Setup a MySQL server and import DB_structure.sql files
4) Edit config.php with your credentials

LICENSE

GNU General Public License v3.0

This is a free software ;)