Routine Responses
========================

Normal Responses
----------------

Normal response documentation still awaited.



Error Responses
---------------

AIFS provide json responses along with a 500 HTTP response code when an error is triggered by the routines scripts.

+--------------+--------------------------------------------+--------------------------------------------+
| Error code | Message | Notes |
+--------------+--------------------------------------------+--------------------------------------------+
| **500** | |
+--------------+--------------------------------------------+--------------------------------------------+
| 500001 | Cannot open sql connection on host. | Your database parameters are wrong or your database is down. |
+--------------+--------------------------------------------+--------------------------------------------+
| 500002 | Impossible to select database on host. | |
+--------------+--------------------------------------------+--------------------------------------------+
| 500003 | Cannot execute query without connection. | Lost connection handle. |
+--------------+--------------------------------------------+--------------------------------------------+
| 500004 | We are unable to execute your request. | Query syntax error. This error trigger higher log level. |
+--------------+--------------------------------------------+--------------------------------------------+
| 500005 | Query not executed. | Data extraction errors, data type errors. |
+--------------+--------------------------------------------+--------------------------------------------+

