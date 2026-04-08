<?php
$tutorial_title = 'Git';
$tutorial_slug  = 'git';
$quiz_slug      = 'git';

$tutorial_tiers = [
    [
        'label'    => 'Introduction',
        'overview' => '<p>Git is the world\'s most widely used distributed version control system, created by Linus Torvalds in 2005 to manage the Linux kernel source code. Git tracks every change made to a codebase, enables multiple developers to work in parallel without overwriting each other\'s changes, and provides a complete history that makes it possible to revert to any previous state. Understanding Git is non-negotiable for every software developer.</p>',
        'concepts' => [
            'Version control concepts: repository, commit, history, branching',
            'git init, git clone: creating and copying repositories',
            'The three areas: working directory, staging area (index), repository',
            'git add, git commit: staging and committing changes',
            'git status, git log, git diff: inspecting state and history',
            'git push, git pull, git fetch: syncing with remotes',
            '.gitignore: excluding files from tracking',
        ],
        'code' => [
            'title'   => 'Git daily workflow',
            'lang'    => 'bash',
            'content' =>
'# Initial setup
git config --global user.name  "Your Name"
git config --global user.email "you@example.com"
git config --global core.editor "code --wait"   # VS Code as editor

# Start a new repo
git init my-project && cd my-project

# Daily workflow
git status                          # what changed?
git diff                            # diff working dir vs. staging
git diff --staged                   # diff staging vs. last commit
git add -p                          # interactively stage hunks
git commit -m "feat: add user auth" # commit with message

# View history
git log --oneline --graph --decorate --all
git show HEAD:src/app.js            # show file at specific commit

# Undo before commit
git restore src/app.js              # discard working dir changes
git restore --staged src/app.js     # unstage (keep working dir)',
        ],
        'tips' => [
            'Use git add -p to review and stage changes hunk by hunk — it forces you to read what you are committing.',
            'Write commit messages in imperative mood: "Add feature" not "Added feature" — match the git log convention.',
            'Set up a global .gitignore (~/.gitignore_global) for OS files (.DS_Store, Thumbs.db) and editor files.',
        ],
    ],
    [
        'label'    => 'Beginner',
        'overview' => '<p>Branching is Git\'s superpower. Branches are cheap, lightweight pointers to commits, enabling parallel lines of development — feature branches, bug fix branches, release branches — that merge back together. Understanding merge strategies (fast-forward, recursive, no-fast-forward) and resolving merge conflicts are the core skills every developer needs for collaborative Git workflows.</p>',
        'concepts' => [
            'Branches: git branch, git switch, git switch -c (create + switch)',
            'Merging: fast-forward merge, 3-way merge, merge commits',
            'Merge conflicts: conflict markers (<<<<<<<, =======, >>>>>>>), resolving manually',
            'git stash: save work-in-progress temporarily',
            'Remote tracking branches: origin/main, git fetch vs. git pull',
            'git push --set-upstream, --force-with-lease',
            'Tags: lightweight and annotated tags for marking releases',
        ],
        'code' => [
            'title'   => 'Feature branch workflow',
            'lang'    => 'bash',
            'content' =>
'# Create feature branch from main
git switch main && git pull
git switch -c feature/user-registration

# Work, commit, push
git add .
git commit -m "feat: add user registration form"
git push -u origin feature/user-registration

# Update branch with latest main changes
git fetch origin
git rebase origin/main   # preferred: linear history
# OR: git merge origin/main

# Handle merge conflict
git diff --diff-filter=U          # show only conflicted files
# Edit conflicted files, then:
git add <resolved-file>
git rebase --continue             # or: git commit (for merge)

# Stash before switching branches
git stash push -m "WIP: half-finished auth"
git switch hotfix/login-bug
# ... fix bug, commit ...
git switch feature/user-registration
git stash pop',
        ],
        'tips' => [
            'Rebase keeps history linear; merge preserves the parallel development. Know your team\'s policy before choosing.',
            'Use git push --force-with-lease instead of --force — it fails if someone else has pushed to the branch.',
            'git stash push -m "description" lets you name stashes — git stash list is much more readable with names.',
        ],
    ],
    [
        'label'    => 'Intermediate',
        'overview' => '<p>Interactive rebase (<code>git rebase -i</code>) lets you rewrite history before merging — squashing commits, reordering them, editing commit messages, or splitting a commit into two. A clean, logical commit history makes code review and git bisect much easier. Cherry-pick applies a specific commit from one branch to another without a full merge.</p><p>Git hooks — scripts triggered by git actions — automate code quality checks (linting, testing) before commits and pushes, catching issues before they reach the remote.</p>',
        'concepts' => [
            'Interactive rebase: rebase -i, squash, fixup, reword, drop, edit',
            'git commit --amend: modify the last commit (message or content)',
            'git cherry-pick: apply specific commits to another branch',
            'git bisect: binary search through history to find the commit that introduced a bug',
            'Git hooks: pre-commit, commit-msg, pre-push scripts in .git/hooks/',
            'Husky + lint-staged: modern git hooks management for JavaScript projects',
            'Submodules: embedding one repository inside another',
        ],
        'code' => [
            'title'   => 'Interactive rebase — clean up commits',
            'lang'    => 'bash',
            'content' =>
'# Squash last 4 commits into one clean commit
git rebase -i HEAD~4
# Interactive editor opens:
# pick a1b2c3d feat: add login page
# squash d4e5f6a fix typo in login
# squash 7g8h9i0 fix another typo
# squash j1k2l3m add missing semicolon
# → closes as one commit with combined message

# Fixup: squash but discard this commit message
git commit --fixup HEAD~2          # marks for fixup
git rebase -i --autosquash HEAD~3  # auto-reorders fixups

# Find the commit that introduced a bug
git bisect start
git bisect bad HEAD              # current commit is bad
git bisect good v2.1.0           # last known good version
# Git checks out the midpoint — test it, then:
git bisect good                  # or: git bisect bad
# Repeat until Git identifies the culprit commit
git bisect reset                 # restore HEAD when done',
        ],
        'tips' => [
            'Never rebase commits that have been pushed to a shared branch — it rewrites history and breaks teammates\' repos.',
            'git bisect is invaluable for large codebases — it finds a regression in O(log n) steps.',
            'Use git commit --fixup for in-flight fixes and --autosquash in the rebase to keep history clean automatically.',
        ],
    ],
    [
        'label'    => 'Advanced',
        'overview' => '<p>Advanced Git covers the object model — how Git stores blobs, trees, commits, and tags as content-addressed SHA-1 objects in the .git/objects directory. Understanding this model explains why operations like branching are instant and why rebasing rewrites object hashes. The reflog is the safety net: it records every position HEAD has been in, letting you recover from "lost" commits after a reset or rebase.</p><p>Gitflow, GitHub Flow, Trunk-Based Development, and the Conventional Commits standard are the workflow and convention choices that teams make to structure their collaboration.</p>',
        'concepts' => [
            'Git object model: blob, tree, commit, tag; SHA-1 content addressing',
            'git cat-file, git ls-tree, git rev-parse for low-level inspection',
            'The reflog: git reflog, recovering from --hard reset, rebase disasters',
            'git worktree: multiple working directories from the same repository',
            'Shallow clones: --depth for CI speed; git fetch --unshallow',
            'Conventional Commits: feat:, fix:, chore:, BREAKING CHANGE:',
            'Workflow strategies: Gitflow, GitHub Flow, Trunk-Based Development, Release Flow',
        ],
        'code' => [
            'title'   => 'Reflog — rescue lost commits',
            'lang'    => 'bash',
            'content' =>
'# Oops — accidentally reset to the wrong commit
git reset --hard HEAD~5   # just lost 5 commits!

# Recover with reflog
git reflog               # shows recent HEAD positions with timestamps
# HEAD@{0}: reset: moving to HEAD~5
# HEAD@{1}: commit: feat: add checkout flow
# HEAD@{2}: commit: feat: add cart
# ...

# Restore to before the reset
git reset --hard HEAD@{1}   # go back to the commit before the reset

# Or: create a new branch at the lost commit
git switch -c recovery HEAD@{1}

# Reflog is your safety net — entries stay for 90 days by default
# (controlled by gc.reflogExpire)

# Inspect a blob/tree/commit object
git cat-file -t a1b2c3d    # type: commit / blob / tree / tag
git cat-file -p a1b2c3d    # print contents
git ls-tree HEAD           # show tree of current commit',
        ],
        'tips' => [
            'The reflog is your safety net — nearly every "I just lost my work" situation is recoverable with git reflog.',
            'Use git worktree add ../feature-branch feature for parallel work without stashing.',
            'Adopt Conventional Commits — it enables automated changelog generation and semantic versioning.',
        ],
    ],
    [
        'label'    => 'Expert',
        'overview' => '<p>Expert Git involves deep knowledge of the pack file format (how Git compresses object storage), the index file binary format, and writing custom Git commands and hooks in any language. Understanding SHA-256 migration (Git 2.29+), the partial clone and sparse checkout features for monorepo performance, and signed commits with GPG/SSH are the capabilities that distinguish Git experts from proficient users.</p>',
        'concepts' => [
            'Pack files: git pack-objects, git repack, delta compression',
            'Partial clones: --filter=blob:none for blobless clones in CI',
            'Sparse checkout: git sparse-checkout for monorepo subdirectory work',
            'GPG/SSH commit signing: git commit -S, git log --show-signature',
            'Git protocols: HTTP/HTTPS smart protocol, SSH, local file:// protocol',
            'Git server setup: bare repositories, git-daemon, gitolite, Gitea',
            'SHA-256 transition: git init --object-format=sha256 (Git 2.29+)',
        ],
        'code' => [
            'title'   => 'Partial clone and sparse checkout for monorepos',
            'lang'    => 'bash',
            'content' =>
'# Blobless clone: fetch all commits/trees but no file contents
# (Git downloads blobs on demand — dramatically faster for large repos)
git clone --filter=blob:none https://github.com/org/monorepo

# Sparse checkout: only work with specific subdirectories
git sparse-checkout init --cone
git sparse-checkout set services/api services/auth packages/shared

# Now the working directory only contains those directories
git sparse-checkout list    # see what is checked out

# Sign commits with SSH key (Git 2.34+)
git config --global gpg.format ssh
git config --global user.signingkey ~/.ssh/id_ed25519.pub
git config --global commit.gpgsign true

# Verify signatures
git log --show-signature
git verify-commit HEAD',
        ],
        'tips' => [
            'Blobless clones (--filter=blob:none) reduce CI checkout time from minutes to seconds on large repos.',
            'Sign commits in open-source projects — GitHub shows a "Verified" badge and it proves authorship.',
            'Follow gitster\'s blog and the git mailing list (vger.kernel.org) for deep dives into git internals.',
        ],
    ],
];

require_once __DIR__ . '/tutorial-engine.php';
