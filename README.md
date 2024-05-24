## Setup project:

-   Change .env.example to .env
-   Run `composer install`
-   Run `npm run build`
-   Update the PHP_ENV to show the controls for starting and terminating horizon, else you'll have to perform these action manually through the cli

## Perform runs

1. Visit the project in the browser
2. Start horizon
3. Add 1 job via the UI
4. Terminate horizon
5. Now the job will stop progressing, or it will continue. This seems to be completely random
6. Clear jobs in the UI to reset.
7. Repeat step 1 to 6 until you experience a job getting stuck after terminating horizon

## Important notes

-   The behaviour of the worker stopping too soon only happens when there is 1 job being processed
-   When there are multiple jobs in the queue or being processed the ungraceful shutdowns don't seem to appear
