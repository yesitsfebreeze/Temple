#!/usr/bin/env bash
set -o errexit #abort if any command fails
me=$(basename "$0")

help_message="\
Usage: $me [-c FILE] [<options>]
Deploy generated files to a git branch.

Options:

  -h, --help               Show this help information.
  -v, --verbose            Increase verbosity. Useful for debugging.
  -e, --allow-empty        Allow deployment of an empty directory.
  -m, --message MESSAGE    Specify the message used when committing on the
                           deploy branch.
  -n, --no-hash            Don't append the source commit's hash to the deploy
                           commit's message.
  -c, --config-file PATH   Override default & environment variables' values
                           with those in set in the file at 'PATH'. Must be the
                           first option specified.

Variables:

  GIT_DEPLOY_DIR      Folder path containing the files to deploy.
  GIT_DEPLOY_BRANCH   Commit deployable files to this branch.
  GIT_DEPLOY_REPO     Push the deploy branch to this repository.

These variables have default values defined in the script. The defaults can be
overridden by environment variables. Any environment variables are overridden
by values set in a '.env' file (if it exists), and in turn by those set in a
file specified by the '--config-file' option."

bundle exec middleman build --clean