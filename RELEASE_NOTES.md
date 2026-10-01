New major release. Must be on 5.3.7 before upgrading. Requires PHP 8.2+ and MariaDB 10.4+ / MySQL 8.0+

**Improvements**
- Refactor the database to a modern, secure and performing structure
- Improve performance in calendar loading
- Add return code 97 for LDAP anonymous bind failed
- Update dependencies
- Enhance LDAP configuration documentation and improve connection handling in LdapService ([Issue](#112))
- Enhance security in dispatchLegacy by validating action names to prevent local file inclusion ([Issue](#111))

**Removals**
- Remove obsolete Chart.js 4.4.7 folder (new version installed)
- Remove Syntaxhighlighter addon (not used anymore)