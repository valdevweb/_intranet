1. ajout des champs à magasin.sca3

ALTER TABLE `sca3`  ADD `pole_sav` INT NULL  AFTER `pole_sav_sca`,  ADD `antenne_sav` INT NULL  AFTER `pole_sav`;


2. maj champ avec données table sav.mag

=> ok et poussé en prod

3. suppression dans sca3 champ pole_sav_sca

=> a faire sur prod


4. base magasin table mag : suppression pole_sav et antenne

=> a faire sur prod


5. maj fiche mag et base mag
6. maj id_web_user dans sca3 à jour
   => table sca3 à pousser en prod


FAIT EN DEV 

base btlec :
- suppression  magsyno 
- suppression sca3


base sav :
- suppression table mag


base user :
- suppression mag
- suppression mag_centrales
