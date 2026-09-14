Security release. Updating is strongly recommended.

**Bugfixes**
- Fix forgeable login cookie that allowed an authentication bypass
- Fix bad login counter not being persisted, leaving brute force protection ineffective
- Fix bad login lock never expiring and being confused with an administrative account lock
- Fix login grace period being compared against an unparsed date string

**Improvements**
- Add license reetrieval error to TCN log