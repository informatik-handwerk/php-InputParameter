# Symfony/Doctrine consists of various mutually incompatible systems

- there are the Expressions, which are feedable into Criteria which can be fed into QueryBuilder.
- but they cannot be fed into QueryBuilder directly, there is a different namespace for that.
- ...possibly has more of unclean design 

#### 2022-April-15 21:42:30 : created from `dev`

Has already been implemented, only to commit it.
Chenges the API, will have to bump up major version.


