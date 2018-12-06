# mapsubmission
Submission collection for a AoE2 rms contest

## Setup

1. Copy all files and folders in this repository to a folder of your choice on 
   your web server
2. Make the `data` directory and especially the `participants.json` file inside 
   writable for your web server
3. Protect the `judge` directory with Basic Auth or something similar
4. Configure your contest in `includes/config.php`

## Usage

A contest takes part in four stages:

1. Before the time configured in `$start`, the main page just displays the title 
   and the start/end times of the contest. The judging area (`/judge`) displays a 
   "judging has not started yet" message.
2. After `$start` and before `$end`, the main page displays a submission form, where
   participants can submit their random map script. When multiple random map scripts
   are submitted under the same author name, all are stored, but only the last
   submission is displayed to the judges and in the results. The judging area still
   displays a "judging has not started yet" message.
3. After `$end`, no submissions are possible anymore, and the main page displays a
   "judging in progress" message. The judging area displays all submissions (without
   the author names, but still only the latest submission per author). For each random
   map script, Judges can give points in various categories and add a comment.
4. When `$published` is changed from `false` to `true`, the judging area displays a 
   "judging has finished" message. The main page displays all submissions together
   with the average rating in each category, the sum of those averages as total score,
   as well as all judges' comments. The submissions are ordered by total score, and 
   the submission(s) with the highest score have a "1st place" marker.
