# Changelog


## v1.18

### What's Changed

_(most recent changes are listed on top):_
- Optimized time calculation further
- Task times on the search results now do not calculate depending on the shown tasks, but on ALL found tasks, despite the paginator page


## v1.17

### What's Changed

_(most recent changes are listed on top):_
- Improved loading times / calculation speed


## v1.16

### What's Changed

_(most recent changes are listed on top):_
- Slower / faster time calculation added. So basically a calculation, which can show (on task summary), if you worked more or less than estimated.
- Added the new better remaining time calculation into the task list as well.
- Some fixes.


## v1.15

### What's Changed

_(most recent changes are listed on top):_
- Added progress bar under task summary on task site.
- Moved percentage-calculation into a dedicated method for it.
- Added percent number in the task list.
- Added percent number on home screen per project.
- Added option for the user to chose which level to use for the calculation on the home for the projects.


## v1.14

### What's Changed

_(most recent changes are listed on top):_
- User can now also define the swimlane for the time calculation in the config.


## v1.13

### What's Changed

_(most recent changes are listed on top):_
- Remaining time calculation improved.
- Overtime calculation added.
- Changed Task summary layout a bit.
- Levels are hidden now, if no caption is set.
- Added spent+overtime calculation in the hour headings.
- Added remaining time in column header on board.
- Added remaining time on card.


## v1.12

### What's Changed

_(most recent changes are listed on top):_
- Added the _TaskProgressBar_ feature, yet calculated from the time estimated and time spent data of a task, if no subtasks exist.


## v1.11

### What's Changed

_(most recent changes are listed on top):_
- Replaced some templates to get better readable time formatting in certain spots of the app


## v1.10

### What's Changed

_(most recent changes are listed on top):_
- Added responsive design (RWD)
- Time display designs further optimized


## v1.9

### What's Changed

_(most recent changes are listed on top):_
- Time display designs optimized


## v1.8

### What's Changed

_(most recent changes are listed on top):_
- Board HoursView now uses search filter as well


## v1.7

### What's Changed

_(most recent changes are listed on top):_
- Level will be shown with 0 time, if it has a label now


## v1.6

### What's Changed

_(most recent changes are listed on top):_
- Added levels categorization to select columns, which should be summed up in a group and which can be labelled
- Added more intuitive time printing


## v1.5

### What's Changed

_(most recent changes are listed on top):_
- Renamed plugin again


## v1.4

### What's Changed

_(most recent changes are listed on top):_
- Renamed plugin


## v1.3

### What's Changed

_(most recent changes are listed on top):_
- Moved the calculation methods into a helper
- Added time calculations into the search results

## v1.2

### What's Changed

_(most recent changes are listed on top):_
- Changed that "remaining" calculation could fall below zero, thus not representing not-started-yet tasks to correctly be represented in the time calculation. This could happen, if you would spend more time for tasks than the estimation was, thus subtracting too much from the overall calculation. The fix is to substract the estimated time at max.


## v1.1

### What's Changed

_(most recent changes are listed on top):_
- Added times info on the dashboard


## v1.0

### What's Changed

_(most recent changes are listed on top):_
- Initial release
- Translations starter template included


Read the full [**Changelog**](../master/changelog.md "See changes") or view the [**README**](../master/README.md "View README")
