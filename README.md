# Cooperative Production for Akaunting

Independent, AGPL-3.0-or-later Akaunting module foundation for the cooperative
production migration. The Symfony application remains the financial source of
truth until an independently authorized cutover.

## Current capabilities

* PHP 8.3+ / BCMath, framework-independent economic kernel transported from
  the approved legacy port, without creating a second economic algorithm.
* Non-destructive snapshot and journal migration with deliberately inert
  rollback; no finance-writing routes or commands.
* Read-only Kimai monthly source reader and encrypted snapshot capture
  components adapted from the draft legacy PR #293. The reader rejects
  incomplete pagination; absence is never interpreted as deletion.
* No individual portal, Nextcloud API, bank integration, approval, or financial
  publication.

## Install in a disposable Akaunting environment

Copy the **contents of this repository** into `modules/CooperativeProduction`
of the matching Akaunting instance. Confirm the server has BCMath enabled.
Install/enable using the target instance's normal Akaunting module installation
workflow. No production deployment is authorized by this README.

The provider registers only translations and durable migrations. Migrations
create only `lcc_pdc_*` tables; the rollback deliberately retains journal
records. Database installation must first be validated on an isolated disposable
database. Do not run Artisan migrations against shared production services.

## Safety gate

This is **not yet a complete first release**: a native preview with verified
company ACL and financial source reader, complete computation memory and native
PHPUnit Feature tests are required before production use. The existing kernel
still uses floating-point intermediates; do not interpret its output as proved
end-to-end cent-accurate financial settlement.

## Origin and scope

Economic kernel and snapshot schema: LibreCodeCoop/producao-cooperativista
PRs #284, #285, #286, #289 and #291. Kimai reader: PR #293 (draft), imported
for further review, not treated as merged. Epic #275, issues #277–#279.
