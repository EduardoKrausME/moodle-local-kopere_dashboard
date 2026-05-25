# Kopere Dashboard

The **Kopere Dashboard** is an administrative area created to make day-to-day work easier for those who use Moodle to manage courses, students, documents, requests, reports, and other institutional routines.

The idea is simple: instead of leaving each tool scattered in a different place within Moodle, the Kopere Dashboard brings everything together on a single screen, with a side menu, shortcuts, indicators, and access permissions.

It was designed for teams that need to work with Moodle but do not always want to rely on technical paths or hidden screens inside the administration area.

## What it is for

The Kopere Dashboard works as a work hub inside Moodle.

Through it, the institution can organize resources such as:

- student lookup;
- course lookup;
- academic requests;
- contracts and digital acceptance;
- internal pages;
- documents and certificates;
- user import;
- reports;
- diagnostic tools;
- administrative routines used by the team.

Each institution can use only the modules it needs. One Moodle environment may use the dashboard only for reports. Another may use it for contracts, payments, requests, and support. The structure allows gradual growth.

## How to access

This link is usually used by administrators, coordinators, registrar staff, finance teams, support teams, or other members of the internal staff.

What each person sees depends on the permissions they have received. This prevents a user from seeing areas that are not part of their work.

For example:

- someone from the registrar’s office may access requests and documents;
- someone from the finance team may access charges and payments;
- someone from coordination may access courses, students, and reports;
- an administrator may manage plugins, permissions, and audits.

## What appears on the home screen

The Kopere Dashboard home screen may display cards with important numbers, shortcuts, and useful information for the team.

These cards are called indicators or KPIs. They may show, for example:

- number of open requests;
- pending contracts;
- imported students;
- outstanding payments;
- monitored courses;
- important alerts or pending items.

Not every module needs to have an indicator on the home screen. It only appears when it makes sense for the institution’s routine.

## Organization by modules

The Kopere Dashboard is divided into modules.

This means that each resource can be created separately, without mixing everything into a single codebase. This organization makes maintenance, updates, and the creation of new areas easier.

Examples of modules that may exist in the dashboard:

| Module | What it does |
| --- | --- |
| `users` | Helps search and monitor users. |
| `courses` | Helps search and monitor courses. |
| `requests` | Organizes academic or administrative requests. |
| `contracts` | Manages digital contracts and acceptance of terms. |
| `pages` | Creates internal pages for the institution. |
| `attest` | Works with documents, certificates, or ID cards. |
| `userimport` | Assists with user import. |
| `backup` | Brings together routines related to backup. |
| `benchmark` | Helps evaluate the Moodle environment. |
| `reportcard` | Displays the student’s academic information. |

The list of modules may change depending on the installation and as new resources are added.

## Menu categories

To make navigation easier, modules may appear separated by area.

The most common categories are:

- **Academic**: courses, students, documents, report cards, and requests;
- **Pedagogical**: monitoring, reports, and teaching-related resources;
- **Financial**: payments, contracts, and charges;
- **Tools**: imports, diagnostics, backups, and utilities;
- **Settings**: permissions, plugins, and administrative adjustments.

This division helps the team find what they need without having to know the technical side of Moodle.

## Permissions

The Kopere Dashboard respects permissions.

This means each person can receive access only to the screens required for their work.

The main dashboard permissions are:

```text
local/kopere_dashboard:view
local/kopere_dashboard:viewaudit
local/kopere_dashboard:managepermissions
````

In simple terms:

* `local/kopere_dashboard:view` allows access to the dashboard;
* `local/kopere_dashboard:viewaudit` allows viewing audit records;
* `local/kopere_dashboard:managepermissions` allows managing permissions and modules.

In addition to these, each module may have its own permissions. This allows a person to access one part of the dashboard without having access to all the others.

## Audit

The dashboard has an audit area.

It is used to record important actions performed within the system, such as creating, changing, deleting, or executing a routine.

This helps the institution answer questions such as:

* who made a specific change;
* when the action happened;
* in which module the action was performed;
* which record was affected;
* where the access came from.

The audit should not be seen as a screen for everyone’s daily use. It is more useful for administrators, support teams, and teams that need to investigate changes or monitor sensitive operations.

## Internal modules

Internal modules are located in:

```text
local/kopere_dashboard/plugins/
```

Each folder inside this directory represents a dashboard module.

For example:

```text
local/kopere_dashboard/plugins/courses
local/kopere_dashboard/plugins/users
local/kopere_dashboard/plugins/requests
```

These modules appear in the menu when they are installed, active, and when the user has permission to access them.

## Compatible external plugins

In addition to internal modules, the dashboard can also recognize some external Moodle plugins.

This is useful when a resource is too large to stay inside the dashboard’s `plugins/` folder or when it needs to be distributed separately.

In this case, the external plugin must follow the `local_kopere_*` pattern and provide the required integration classes to appear in the menu and permissions screen.

In practice, this allows several plugins from the Kopere family to communicate with the same dashboard, while maintaining a unified experience for the end user.

## Module management

The management screen is located at:

```text
/local/kopere_dashboard/admin_plugins.php
```

On this screen, the administrator can organize which modules appear in the dashboard and in what order.

This makes it possible to adapt the menu according to the institution’s reality. One environment may highlight academic requests. Another may prioritize contracts and finance. Another may use the dashboard as a reporting hub.

## Visual standard

Module screens should follow the dashboard’s own visual style.

This prevents each resource from having a different appearance and improves the experience of those who use the system every day.

The dashboard already has a layout structure for building:

* side menu;
* main content;
* page header;
* settings area;
* breadcrumbs;
* footer;
* shared visual identity.

Therefore, when creating a new screen, the ideal approach is to use the Kopere Dashboard’s standard layout instead of building a completely separate page.

## Best practices

When creating or adjusting modules for the Kopere Dashboard, keep a few precautions in mind:

* write simple screens that are easy to understand;
* use clear text, also considering non-technical users;
* keep permissions well separated;
* record important actions in the audit;
* avoid mixing business rules directly into pages;
* use Mustache templates to build the interface;
* place texts in language files;
* keep each module responsible for a clear function.

The goal is for the dashboard to remain easy to maintain and easy to use, even when new modules are added.

## Creating new modules

The documentation for creating a new module is located at:

* EN: [plugins/create-plugin-en.md](plugins/create-plugin-en.md)
* BR: [plugins/create-plugin-pt_br.md](plugins/create-plugin-pt_br.md)

This file explains the expected structure, main files, menu, permissions, and the correct way to integrate the new module with the dashboard.

## Summary

The Kopere Dashboard is an administrative hub for Moodle.

It brings together modules, permissions, indicators, audit, and menus into a single experience. This allows the institution’s team to access important resources in one place, without having to search for screens scattered throughout Moodle administration.

It also makes project growth easier, because new modules can be added as new needs arise.
