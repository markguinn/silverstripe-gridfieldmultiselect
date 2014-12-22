Multi-Select Checkboxes for SilverStripe's GridField
====================================================

GridField components that add checkboxes to each row in a grid and make
it easy to perform actions on multiple rows.

Requirements
------------
- Silverstripe 3.1+


Example: Delete
---------------
```
$gridFieldConfig->addComponents(
    new GridFieldCheckboxSelectComponent(),
    new GridFieldMultiDeleteButton()
);
```

Example: Custom Action
----------------------
```
$gridFieldConfig->addComponents(
    new GridFieldCheckboxSelectComponent(),
    new GridFieldApplyToMultipleRows(
        'emailClients', 
        'Email Selected Clients',
        function($record, $index){
            $record->sendEmailToClient();
        }
    )
);
```


Developer(s)
------------
- Mark Guinn <mark@adaircreative.com>

Contributions welcome by pull request and/or bug report.
Please follow Silverstripe code standards (tests would be nice).


License (MIT)
-------------
Copyright (c) 2014 Mark Guinn

Permission is hereby granted, free of charge, to any person obtaining a copy of
this software and associated documentation files (the "Software"), to deal in
the Software without restriction, including without limitation the rights to use,
copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the
Software, and to permit persons to whom the Software is furnished to do so, subject
to the following conditions:

The above copyright notice and this permission notice shall be included in all copies
or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED,
INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR
PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE
FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR
OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
DEALINGS IN THE SOFTWARE.
