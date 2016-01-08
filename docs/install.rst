How to install
========================


.. code-block:: bash
   root@srv:/var/www# git clone https://github.com/digitaloversight/aifs.git
   root@srv:/var/www# mysql -u root -p < ./aifs/schema/aifs-base-1.02.sql

.. code-block:: mysql
   mysql> insert into user set Host='localhost', User='aifs', Password=PASSWORD('');
   mysql> flush privileges;
   mysql> grant all on aifs.*  to 'aifs'@'localhost';


=================
Configuration
=================

Make sure you reviwed the configuration on the following files

.. note::
   /config/config.php
   /config/tool/DomainSelector.php
   /common/sql/Sql.php


=================
Extends
=================

To be sure you are ready to go, please take a look at the aifs-extends repository available on github.