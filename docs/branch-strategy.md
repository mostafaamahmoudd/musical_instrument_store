# Branch Strategy

This repository follows a simple GitHub feature-branch workflow with a stable `master` branch and
an integration `dev` branch.

## Branch Roles

- `master` - stable releases and milestones.
- `dev` - integration branch for completed features.
- `feature/*` - feature work branched from `dev`.
- `db/*` - database or migration work branched from `dev`.
- `docs/*` - documentation work branched from `dev`.
- `setup/*` - environment and tooling setup tasks branched from `dev`.

## Merge Flow

1. Branch from `dev` into `feature/*`, `db/*`, `docs/*`, or `setup/*`.
2. Merge completed branches back into `dev`.
3. Merge `dev` into `master` for a release or milestone.
