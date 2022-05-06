# Lists are conceptually not quite clean; mixed list not possible

- [x] Current lists silently expect all items having same name. Should check their consistency.
- [ ] There is no mixed list - yet this is a super common requirement.

#### 2022-May-06 12:35:27 : created from `dev`

#### 2022-May-06 12:40:41 : comment

The list has to know the name, since it can be empty.

#### 2022-May-06 23:31:48 : comment

Parsing and __toString() of InputParameter_Collection is a problematic theme, since this is interfacing outside system. The other InputParameters, they only __toString their value, not their name, since this would require knowing the embedding format. The `InputParameter::stringify()` function was written under wrong assumptions, too quick to simply \implode all values together. The actual method is in the SymfonyBridge_CommandInput.
