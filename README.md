# Git Workflow using Gitflow

The Gitflow Workflow defines a strict branching model designed around the project release. This provides a robust framework for managing larger projects. 

Gitflow is ideally suited for projects that have a scheduled release cycle. This workflow doesn’t add any new concepts or commands beyond what’s required for the Feature Branch Workflow. Instead, it assigns very specific roles to different branches and defines how and when they should interact. In addition to feature branches, it uses individual branches for preparing, maintaining, and recording releases. Of course, you also get to leverage all the benefits of the Feature Branch Workflow: pull requests, isolated experiments, and more efficient collaboration.

## Getting Started

Initial readings:

[What is Version Control](https://www.atlassian.com/git/tutorials/what-is-version-control)
* Git basics and setup

[Feature Branch Workflow](https://www.atlassian.com/git/tutorials/comparing-workflows/feature-branch-workflow)
* Much simpler workflow that is being incorporated in Gitflow

[Git Branching Model](https://nvie.com/posts/a-successful-git-branching-model/)
* Original Gitflow concept

[Gitflow Workflow](https://www.atlassian.com/git/tutorials/comparing-workflows/gitflow-workflow)
* Guide and documentation by Atlassian

## Prerequisites

* Git installed in the hosting server (check via SSH)
* Public project repository in Github account
* Staging site installed
* Local environment (xampp, wampp, easyPHP)
* Knowledge in git

## Setup

### Github

* Login to GitHub account.
* Add new site repository folder.

### Local

* Deploy a copy of the production site to local environment.
* Deactivate unnecessary plugins
* Make sure child theme is active, if none see How to Create a Child Theme
* Open gitbash on local "/wp-content" folder. Initialize git.
```
(master)$ gitinit
```
* Add a standard copy of the .gitignore file and modify accordingly
> Guideline: only track working directory, child theme, custom plugins, etc.
* Check tracked files.
```
(master)$ git ls-files
```
* Copy git clone url from Github and add as remote "origin".
```
(master)$ git remote add origin <git url>
```
* In git bash, checkout develop branch and add inital commit.
```
(master)$ git checkout -b develop
(develop)$ git status
(develop)$ git add *
(develop)$ git commit -m "initial commit"
(develop)$ git push --set-upstream origin develop
```

### Staging

* Install and activate new plugin WP Pusher. 
* Go to WP Pusher>GitHub, obtain github token and save.
* In WP Pusher>Install Theme. 
  - Pick from Github: repository folder
  - Repository branch: develop
  - Repository subdirectory: themes/child-theme
  - Check option Link installed theme
  - Install and activate
* In WP Pusher>Themes, review Deploy Info. 
  - *Warning* Do not update theme if the repository is still being worked on. Consult dev lead.

### Production

* Install and activate new plugin WP Pusher. 
* Go to WP Pusher>GitHub, obtain github token and save.
* In WP Pusher>Install Theme. 
  - Pick from Github: repository folder
  - Repository branch: master
  - Repository subdirectory: themes/child-theme
  - Check option Link installed theme
  - Install and activate.
* In WP Pusher>Themes, review Deploy Info. 
- *Warning* Do not update theme if the repository is still being worked on. Consult dev lead.


## Development and Deployment

### Getting Started

From issue to release. This is the development workflow with JIRA and git. A guide on moving the issue status, creating branches, merging, and pushing. Also an overview of the release deployment process.

#### Develop and Master

Aside from the Gitflow documentation, this is basically master=Production and develop=Staging. We push all our changes to develop/Staging so that we can test before pushing it to master/production. Unlike other git workflows, develop does not directly merge into master. This separation is magnified by Pull Requests for a better control on what goes to production.

#### Feature vs Release vs Hotfix

When an grabbing an issue, the first thing to do is identify the task at hand.

Feature Branch
- Story or task issues go to a new feature branch which is checked out from develop.
- When a feature is complete it gets merged back to develop for testing.
- Feature branches should never interact with master.
- Naming convention must be followed for clarity, see "Pushing changes from local to develop".

Release Branch
- When develop has acquired enough issues for a release or a schedule is incoming, a release branch is checked out from develop to create a stable version for deployment.
- Other features can be merged to develop without affecting the release branch.
- The release branch is pushed to master via Pull Request.
- Naming convention must be followed for clarity, see "Releasing to Production".

Hotfix Branch
- When a bug is discovered in master, a hotfix branch is checked out from master.
- After fixing the branch is merged to develop for testing.
- Like the release branch, a Pull Request is made to push the changes to master.
- Naming convention must be followed for clarity, see "Handling Hotfixes".


### Pushing changes from local to Staging

- Issues are updated to "Dev Ready" for development.
- Developer looks at JIRA, grabs a Story or Task and moves it to "Development Ready".
- Open gitbash on local site folder.
- Update develop branch and checkout new branch with the ticket id.
```
(develop)$ git pull origin develop
(develop)$ git checkout -b TCK-1
```
- Start development. Add files and commit the changes.
- Update develop branch and merge your feature.
```
(TCK-1)$ git checkout develop
(develop)$ git pull origin develop
(develop)$ git merge --no-ff TCK-1
(develop)$ git push origin develop
```
- Open Staging site>Admin>WP Pusher and update the target Theme/Plugin.
- In JIRA, move issue to "Ready for QA".
- If not accepted, repeat development process.

### Releasing to Production

- When ready for a release, checkout a release branch and push.
- Naming convention is release/version, version number is based on "Semantic Versioning".
```
(develop)$ git checkout -b release/1.0.0
(release/1.0.0)$ git push --set-upstream origin release/1.0.0
```
- In JIRA, review and update all related issues to this version.
- When all issues  are in "Awaiting Release" and is ready for deployment, create a Pull Request in Github using:
> base:master <- compare:release/1.0.0".
- Dev Lead approves and merges the request, then creates the Release tag on master.
- Dev Lead updates issues to "Released in Production", then merges the branch to develop.
- Make sure Staging Repository Branch is develop.

Inserted issues
- If a new issue should to be added in the release after the branch has been created, merge the feature branch to the release branch instead of develop.
```
(develop)$ git checkout release/1.0.0
(release/1.0.0)$ git pull origin release/1.0.0
(release/1.0.0)$ git merge --no-ff TCK-2
(release/1.0.0)$ git push origin release/1.0.0
```
- In Staging WP Pusher>Themes, Edit Theme and change the Repository Branch to the release branch then save.
- Update the corresponding Theme and test. 
- When the issue is approved proceed with the deployment.

### Handling Hotfixes
- If issue is a bug, create a hotfix branch from master.
- Naming convention is hotfix/version, version number is based on "Semantic Versioning".
```
(master)$ git checkout -b hotfix/1.0.1
```
- Add your changes, commit, and push to develop to test.
```
(hotfix/1.0.1)$ git commit -m "fixed critical issue"
(hotfix/1.0.1)$ git checkout develop
(develop)$ git pull origin develop
(develop)$ git merge --no-ff hotfix/1.0.1
(develop)$ git push origin develop
```
- Once approved, update ticket to "Awaiting Release" and create a Pull Request.
> "base:master <- compare:hotfix/1.0.1"
- Dev Lead approves and merges these, then creates the Release tag on master.
- Dev Lead updates the issues to "Released in Production", then merges the branch to develop.
- Make sure Staging Repository Branch is develop.
- Delete the temporary hotfix branch.
```
(develop)$ git branch -d hotfix/1.0.1
```

## SUMMARY

The overall flow of Gitflow is:
1. A develop branch is created from master.
2. A release branch is created from develop.
3. Feature branches are created from develop.
4. When a feature is complete it is merged into the develop branch.
5. When the release branch is done it is merged into develop and master.
6. If an issue in master is detected a hotfix branch is created from master. Once the hotfix is complete it is merged to both develop and master.

## Other resources:

Merging vs Rebasing
https://www.atlassian.com/git/tutorials/merging-vs-rebasing

Semantic Versioning
https://semver.org/

## Author

**Rad Apdal**
rad.apdal@yempo-solutions.com
