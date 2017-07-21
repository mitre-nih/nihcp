NIHCP Commons Credits Request Review
=========================
This plugin is for the review of Commons Credits Requests.

It has 6 components of review:
   1. Alignment with Commons Credits Objectives
   2. General Score
   3. Risk/Benefit Score
   4. Final Score
   5. Final Recommendation
   6. Final Decision

Each of these components are represented as an ElggObject class under /classes/Nihcp/Entity/.

1. Alignment with Commons Credits Objectives
Stores the answers to different questions on the related form. Overview page should show a Pass or Fail depending on whether all of the questions are satisfied.

2. General Score
Stores up to 3 different GeneralScore entities, depending on which class of digital objects were described in the ccreq under review.
Each object is stored as a different Elgg entity relationship to the ccreq entity.

3. Risk/Benefit Score
Provides an interface for triage coordinators to assign domain experts for review and see the results of their reviews.
Also, provides the form for DE's to submit their respective reviews.

4. Final Score
Should have three properties so far:
sbr: Mean Scientific Benefit to Scientific Risk Ratio
sv: Scientific Value
cf: Computational Factor
Refer to design documents for meanings on these calculations.


General Score, Risk/Benefit Score, and Final Score sections have subsections for each of the Digital Object (DO) classes.
They should only be available for review if the originally submitted credits request has text for those classes.

Numbers will always be shown rounded to the nearest integer.
However, they may be inputted and saved with decimal places.

URL paths follow a convention of:
/[section]/[request_guid]/[subsection, if necessary]
