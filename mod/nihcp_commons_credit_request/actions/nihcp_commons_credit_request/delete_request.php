<?php
/*
Copyright 2017 The MITRE Corporation
 
This software was written for the NIH Commons Credit Portal. General questions 
can be forwarded to:

opensource@mitre.org

Technology Transfer Office
The MITRE Corporation
7515 Colshire Drive
McLean, VA 22102-7539

Permission is hereby granted, free of charge, to any person obtaining a copy 
of this software and associated documentation files (the "Software"), to deal 
in the Software without restriction, including without limitation the rights 
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell 
copies of the Software, and to permit persons to whom the Software is furnished
to do so, subject to the following conditions:
 
The above copyright notice and this permission notice shall be included in all 
copies or substantial portions of the Software.
 
THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR 
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, 
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE 
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER 
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, 
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
*/
 

$guid = get_input('request_guid');
if($guid) {
    $request = get_entity($guid);
	if(!$request instanceof \Nihcp\Entity\CommonsCreditRequest) {
		return false;
	}
    $pricing_upload_guid = $request->pricing_upload_guid;
    $supplementary_materials_upload_guid = $request->supplementary_materials_upload_guid;
    if($pricing_upload_guid) {
        $pricing_upload_file = get_entity($pricing_upload_guid);
        $pricing_upload_file->delete();
    }
    if($supplementary_materials_upload_guid) {
        $supplementary_materials_upload_file = get_entity($supplementary_materials_upload_guid);
        $supplementary_materials_upload_file->delete();
    }
    return $request->delete();
}
return false;