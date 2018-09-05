# Contributing Guidelines

Everyone is welcome to make suggestions o how thisi plugin can be improved. You can do this by either submitting an issue to report a bug or discuss a feature, or a pull-request to fix a bug or add a feature.

### Did you find a bug or other issue?
- Ensure the bug or issue was not already reported by searching on the GitHub repository under [issues](https://github.com/johnstray/gs-users/issues).
- If your unable to find an issue addressing the problem, [open a new one](https://github.com/johnstray/gs-users/issues/new). Be sure to include a title and clear description, plus as much relevant information as possible.
- Use the [xdebug](https://xdebug.org/) extension for PHP if possible an provide the stack trace with the issue.
- For more detailed information about submitting a bug report or creating an issue, view the Reporting Guidelines below.

### Did you write a patch that fixes a bug or other issue?
- Open a new GitHub Pull Request with the patch
- Ensure the Pull Request description clearly describes the problem and the solution you are providing.
- Include the relevant issue number in the description by adding one of either "Fixes \#xx" or "Part of \#xx"
- Before submitting your pull request, please make sure you have followed the Pull Request Guidelines below.

### Do you intend to add a new feature or change an existing one?
- Suggest your change or addition by first creating an issue for discussion which you can then reference to later.
- Add the appropriate label to the issue: New Feature or Enhancement
- Submit a Pull Request containing the change you wish to contribute, ensuring you follow the Pull Request Guidelines below.

## Pull Request Guidelines
- Create a fork of the 'development' branch first, and make sure it's up to date. This helps to prevent conflicts from occuring. 
- Only pull requests to the development branch will be accepted. This ensures that any new code can go through the review process before it reaches the master.
- All commits and pull requests MUST reference a related issue in the comments. If a related issue doesn't exist, please first create a new one.
- Code should follow common practises and the standards as set out in this [GetSimple Wiki](http://get-simple.info/wiki/getsimple_coding) article. This includes:
  - Indenting of code should consist of 4 spaces per indent, never tabs.
  - Each file should contain an empty line at the end, be UTF-8 encoded, and use UNIX based line-endings.
  - To make it easier for others to read and understand the code, it is recomended that you space out the code a bit more. An example is provided below:
    ```php
    if ( exampleFunction($variable) ) {
        $exploded = explode( $variable );
    } else {
        $imploded = implode( $variable );
    }
    
    // Note the spacing around brackets.
    // Curly braces should remain on the same line as the 'if' or 'else' statements
    ```
  - Changes that are cosmetic in nature and don't add anything substantial to the stability or functionality of the project will generally not be accepted.
  - Acceptance of pull requests is at the descretion of the project leader. All contributions to this repository will be appropriatly credited where possible.

## Issue Reporting Guidelines
- Insert issue reporting guidelines here...

## Repository Structure
This repository has the following branches, each with a specific purpose:
- `master` branch: The master contains all properly reviewed code and is ready for release version.
- `beta-testing` branch: Generally this will contain completed features and fixes ready for public beta testing.
- `development` branch: This is where all development occurs. All pull requests and commits will go here.

The review process:
- `development`: All development is done in this branch. Nothing will move on until it has been completed here.
- `beta-tetsing`: Once a feature or bugfix has been completed, it will be moved here so that it can be reviewed and tested.
- `master`: Once everything has been reviewed and tested appropriately, it will be moved here and considered ready for release.
