<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESCTRUCTIVE') OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code


defined('USER_TYPE_ADMIN')        OR define('USER_TYPE_ADMIN', 1);
defined('USER_TYPE_ADVISOR')      OR define('USER_TYPE_ADVISOR',   2);
defined('USER_TYPE_ENTREP')       OR define('USER_TYPE_ENTREP',  3);
defined('USER_TYPE_MODERATOR')       OR define('USER_TYPE_MODERATOR',  4);

defined('USER_STATUS_INIT')       OR define('USER_STATUS_INIT',    0);
defined('USER_STATUS_LIVE')       OR define('USER_STATUS_LIVE',       1);
defined('USER_STATUS_INVITE')     OR define('USER_STATUS_INVITE',    2);
defined('USER_STATUS_INVITED')    OR define('USER_STATUS_INVITED',    3);
defined('USER_STATUS_DELETE')    OR define('USER_STATUS_DELETE',    4);
defined('USER_STATUS_ALL')        OR define('USER_STATUS_ALL',      100);


defined('CHAT_TYPE_PRIVATE')      OR define('CHAT_TYPE_PRIVATE', 1);
defined('CHAT_TYPE_GROUP')        OR define('CHAT_TYPE_GROUP',   2);
defined('CHAT_TYPE_WELCOME')      OR define('CHAT_TYPE_WELCOME', 0);

defined('QUESTION_STATUS_DRAFT')      OR define('QUESTION_STATUS_DRAFT', 0);
defined('QUESTION_STATUS_SUBMITTED')      OR define('QUESTION_STATUS_SUBMITTED', 1);
defined('QUESTION_STATUS_ROUTED')      OR define('QUESTION_STATUS_ROUTED', 2);
defined('QUESTION_STATUS_ACCEPTED')      OR define('QUESTION_STATUS_ACCEPTED', 3);
defined('QUESTION_STATUS_LAUNCHED')      OR define('QUESTION_STATUS_LAUNCHED', 4);

defined('QUESTION_POST_PRIVATE')      OR define('QUESTION_POST_PRIVATE', "private");
defined('QUESTION_POST_PUBLIC')      OR define('QUESTION_POST_PUBLIC', "public");

defined('CHAT_STATUS_INIT')       OR define('CHAT_STATUS_INIT',    0);
defined('CHAT_STATUS_LIVE')       OR define('CHAT_STATUS_LIVE',    1);

defined('TBL_USER_ID')            OR define('TBL_USER_ID',      'id');
defined('TBL_USER_UID')           OR define('TBL_USER_UID',     'uid');
defined('TBL_USER_FNAME')         OR define('TBL_USER_FNAME',   'fname');
defined('TBL_USER_LNAME')         OR define('TBL_USER_LNAME',   'lname');
defined('TBL_USER_ENTERED_CHATS')         OR define('TBL_USER_ENTERED_CHATS',   'entered_chats');
defined('TBL_USER_SELF_COMMENTS')         OR define('TBL_USER_SELF_COMMENTS',   'self_comments');
defined('TBL_USER_OTHER_COMMENTS')         OR define('TBL_USER_OTHER_COMMENTS',   'other_comments');
defined('TBL_USER_REVIEWS')         OR define('TBL_USER_REVIEWS',   'reviews');
defined('TBL_USER_EMAIL')         OR define('TBL_USER_EMAIL',   'email');
defined('TBL_USER_PWD')           OR define('TBL_USER_PWD',     'pwd');
defined('TBL_USER_TYPE')          OR define('TBL_USER_TYPE',    'type');
defined('TBL_USER_CATEGORY')          OR define('TBL_USER_CATEGORY',    'category');
defined('TBL_USER_STATUS')        OR define('TBL_USER_STATUS',  'status');
defined('TBL_USER_TIME')        OR define('TBL_USER_TIME',  'last_sign_in');
defined('TBL_USER_GROUP')        OR define('TBL_USER_GROUP',  'group');
defined('TBL_USER_CODE')        OR define('TBL_USER_CODE',  'signup_code');
defined('TBL_USER_SIGNUP')        OR define('TBL_USER_SIGNUP',  'signup_time');
defined('TBL_USER_BIO')           OR define('TBL_USER_BIO',     'bio');
defined('TBL_USER_PHOTO')         OR define('TBL_USER_PHOTO',   'photo');
defined('TBL_USER_FACEBOOK')         OR define('TBL_USER_FACEBOOK',   'facebook');
defined('TBL_USER_LOCATION')         OR define('TBL_USER_LOCATION',   'location');
defined('TBL_USER_PUBLIC')         OR define('TBL_USER_PUBLIC',   'public_url');
defined('TBL_USER_COMPANY')         OR define('TBL_USER_COMPANY',   'company');
defined('TBL_USER_UNREAD')         OR define('TBL_USER_UNREAD',   'unread');
defined('TBL_USER_CNAME')         OR define('TBL_USER_CNAME',   'c_name');
defined('TBL_USER_CLOCATION')         OR define('TBL_USER_CLOCATION',   'c_location');
defined('TBL_USER_CSUMMARY')         OR define('TBL_USER_CSUMMARY',   'c_summary');
defined('TBL_USER_BLOCKLIST')         OR define('TBL_USER_BLOCKLIST',   'block_list');
defined('TBL_USER_SUMMARY')         OR define('TBL_USER_SUMMARY',   'summary');

defined('TBL_CHAT_ID')            OR define('TBL_CHAT_ID',          'id');
defined('TBL_CHAT_DID')           OR define('TBL_CHAT_DID',         'did');
defined('TBL_CHAT_NAME')          OR define('TBL_CHAT_NAME',        'name');
defined('TBL_CHAT_QUESTIONID')          OR define('TBL_CHAT_QUESTIONID',        'qid');
defined('TBL_CHAT_OCCUPANTS')     OR define('TBL_CHAT_OCCUPANTS',   'occupants');
defined('TBL_CHAT_TYPE')          OR define('TBL_CHAT_TYPE',        'type');
defined('TBL_CHAT_GROUP')          OR define('TBL_CHAT_GROUP',        'group');
defined('TBL_CHAT_OWNER')          OR define('TBL_CHAT_OWNER',        'owner');
defined('TBL_CHAT_STATUS')        OR define('TBL_CHAT_STATUS',      'status');
defined('TBL_CHAT_JID')           OR define('TBL_CHAT_JID',         'jid');
defined('TBL_CHAT_SENDER')        OR define('TBL_CHAT_SENDER',      'sender');
defined('TBL_CHAT_MESSAGE')       OR define('TBL_CHAT_MESSAGE',     'message');
defined('TBL_CHAT_TIME')          OR define('TBL_CHAT_TIME',        'time');

defined('TBL_OPTION_UID')            OR define('TBL_OPTION_UID',      'uid');
defined('TBL_OPTION_UNREAD')            OR define('TBL_OPTION_UNREAD',      'unread');
defined('TBL_OPTION_INVITE')            OR define('TBL_OPTION_INVITE',      'invite');
defined('TBL_OPTION_ACCEPT')            OR define('TBL_OPTION_ACCEPT',      'accept');
defined('TBL_OPTION_APPROVE')            OR define('TBL_OPTION_APPROVE',      'approve');
defined('TBL_OPTION_SUBMIT')            OR define('TBL_OPTION_SUBMIT',      'submit');
defined('TBL_OPTION_COMMENT')            OR define('TBL_OPTION_COMMENT',      'comment');
defined('TBL_OPTION_REVIEW')            OR define('TBL_OPTION_REVIEW',      'review');
defined('TBL_OPTION_ROUTE')            OR define('TBL_OPTION_ROUTE',      'route');
defined('TBL_OPTION_INTERVAL')            OR define('TBL_OPTION_INTERVAL',      'interval');

defined('TBL_COMMENT_ID')          OR define('TBL_COMMENT_ID',        'id');
defined('TBL_COMMENT_WHO')         OR define('TBL_COMMENT_WHO',       'who_uid');
defined('TBL_COMMENT_WHOM')         OR define('TBL_COMMENT_WHOM',       'whom_uid');
defined('TBL_COMMENT_MID')         OR define('TBL_COMMENT_MID',       'message_id');
defined('TBL_COMMENT_TEXT')       OR define('TBL_COMMENT_TEXT',     'comment');
defined('TBL_COMMENT_DATE')       OR define('TBL_COMMENT_DATE',     'date');

defined('TBL_QUESTION_ID')            OR define('TBL_QUESTION_ID',      'id');
defined('TBL_QUESTION_ASKER_ID')            OR define('TBL_QUESTION_ASKER_ID',      'askerid');
defined('TBL_QUESTION_TYPE')            OR define('TBL_QUESTION_TYPE',      'type');
defined('TBL_QUESTION_TITLE')           OR define('TBL_QUESTION_TITLE',     'title');
defined('TBL_QUESTION_CONTEXT')         OR define('TBL_QUESTION_CONTEXT',   'context');
defined('TBL_QUESTION_TAGS')         OR define('TBL_QUESTION_TAGS',   'tags');
defined('TBL_QUESTION_POST')         OR define('TBL_QUESTION_POST',   'post');
defined('TBL_QUESTION_ROUTE_IDS')            OR define('TBL_QUESTION_ROUTE_IDS',      'r_ids');
defined('TBL_QUESTION_WAIT_IDS')            OR define('TBL_QUESTION_WAIT_IDS',      'w_ids');
defined('TBL_QUESTION_JOIN_IDS')            OR define('TBL_QUESTION_JOIN_IDS',      'j_ids');
defined('TBL_QUESTION_ACCEPT_IDS')           OR define('TBL_QUESTION_ACCEPT_IDS',     'a_ids');
defined('TBL_QUESTION_LINKS')         OR define('TBL_QUESTION_LINKS',   'links');
defined('TBL_QUESTION_FNAMES')           OR define('TBL_QUESTION_FNAMES',     'filename');
defined('TBL_QUESTION_URLS')          OR define('TBL_QUESTION_URLS',    'url');
defined('TBL_QUESTION_STATUS')        OR define('TBL_QUESTION_STATUS',  'status');
defined('TBL_QUESTION_TIME')        OR define('TBL_QUESTION_TIME',  'time');

defined('TBL_REVIEW_ID')            OR define('TBL_REVIEW_ID',      'id');
defined('TBL_REVIEW_FROM')            OR define('TBL_REVIEW_FROM',      'from_id');
defined('TBL_REVIEW_TO')            OR define('TBL_REVIEW_TO',      'to_id');
defined('TBL_REVIEW_TEXT')            OR define('TBL_REVIEW_TEXT',      'review');

defined('TBL_BUSINESS_ID')            OR define('TBL_BUSINESS_ID',      'id');
defined('TBL_BUSINESS_UID')            OR define('TBL_BUSINESS_UID',      'uid');
defined('TBL_BUSINESS_SKILL')            OR define('TBL_BUSINESS_SKILL',      'skill');
defined('TBL_BUSINESS_LOOKING')           OR define('TBL_BUSINESS_LOOKING',     'looking');
defined('TBL_BUSINESS_INTERESTING')         OR define('TBL_BUSINESS_INTERESTING',   'interesting');
defined('TBL_BUSINESS_POSITION')         OR define('TBL_BUSINESS_POSITION',   'position');
defined('TBL_BUSINESS_EDUCATION')            OR define('TBL_BUSINESS_EDUCATION',      'education');
defined('TBL_BUSINESS_VNAME')           OR define('TBL_BUSINESS_VNAME',     'venture_name');
defined('TBL_BUSINESS_SUMMARY')         OR define('TBL_BUSINESS_SUMMARY',   'summary');
defined('TBL_BUSINESS_INDUSTRY')           OR define('TBL_BUSINESS_INDUSTRY',     'industry');
defined('TBL_BUSINESS_STAGE')          OR define('TBL_BUSINESS_STAGE',    'stage');
defined('TBL_BUSINESS_EMPLOYEE')        OR define('TBL_BUSINESS_EMPLOYEE',  'employee_num');
defined('TBL_BUSINESS_FUNDING')        OR define('TBL_BUSINESS_FUNDING',  'funding');

defined('TBL_LINK_ID')            OR define('TBL_LINK_ID',      'id');
defined('TBL_LINK_IMAGE')            OR define('TBL_LINK_IMAGE',      'image');
defined('TBL_LINK_TITLE')            OR define('TBL_LINK_TITLE',      'title');
defined('TBL_LINK_LINK')            OR define('TBL_LINK_LINK',      'link');

defined('TBL_INVITE_NUM')            OR define('TBL_INVITE_NUM',      'num');
defined('TBL_INVITE_ID')            OR define('TBL_INVITE_ID',      'id');
defined('TBL_INVITE_TYPE')            OR define('TBL_INVITE_TYPE',      'type');
defined('TBL_INVITE_CODE')            OR define('TBL_INVITE_CODE',      'code');
defined('TBL_INVITE_MCODE')            OR define('TBL_INVITE_MCODE',      'm_code');
defined('TBL_INVITE_REMAIN')            OR define('TBL_INVITE_REMAIN',      'remain');
defined('TBL_INVITE_MREMAIN')            OR define('TBL_INVITE_MREMAIN',      'm_remain');
defined('TBL_INVITE_REQUEST')            OR define('TBL_INVITE_REQUEST',      'request');
defined('TBL_INVITE_MREQUEST')            OR define('TBL_INVITE_MREQUEST',      'm_request');

defined('TBL_GROUP_NUM')            OR define('TBL_GROUP_NUM',      'no');
defined('TBL_GROUP_NAME')            OR define('TBL_GROUP_NAME',      'name');
defined('TBL_GROUP_IMAGE')            OR define('TBL_GROUP_IMAGE',      'image');
defined('TBL_GROUP_CODE')            OR define('TBL_GROUP_CODE',      'code');
defined('TBL_GROUP_MEMBER')            OR define('TBL_GROUP_MEMBER',      'member');

defined('TBL_SUM_UID')            OR define('TBL_SUM_UID',      'uid');
defined('TBL_SUM_UNREAD')            OR define('TBL_SUM_UNREAD',      'unread');
defined('TBL_SUM_INVITE')            OR define('TBL_SUM_INVITE',      'invite');
defined('TBL_SUM_APPROVE')            OR define('TBL_SUM_APPROVE',      'approve');
defined('TBL_SUM_SUBMIT')            OR define('TBL_SUM_SUBMIT',      'submit');
defined('TBL_SUM_ACCEPT')            OR define('TBL_SUM_ACCEPT',      'accept');
defined('TBL_SUM_COMMENT')            OR define('TBL_SUM_COMMENT',      'comment');
defined('TBL_SUM_REVIEW')            OR define('TBL_SUM_REVIEW',      'review');
defined('TBL_SUM_ROUTE')            OR define('TBL_SUM_ROUTE',      'route');
defined('TBL_SUM_INTERVAL')            OR define('TBL_SUM_INTERVAL',      'interval');


defined('TBL_LEADER_ID')            OR define('TBL_LEADER_ID',      'id');
defined('TBL_LEADER_CODE')            OR define('TBL_LEADER_CODE',      'code');
defined('TBL_LEADER_NAME')            OR define('TBL_LEADER_NAME',      'name');
defined('TBL_LEADER_USERS')            OR define('TBL_LEADER_USERS',      'users');

defined('TBL_HISTORY_ID')            OR define('TBL_HISTORY_ID',      'no');
defined('TBL_HISTORY_MID')            OR define('TBL_HISTORY_MID',      'msg_id');
defined('TBL_HISTORY_LIKE')            OR define('TBL_HISTORY_LIKE',      'like');
defined('TBL_HISTORY_SAVE')            OR define('TBL_HISTORY_SAVE',      'save');
defined('TBL_HISTORY_MSG')            OR define('TBL_HISTORY_MSG',      'update');
defined('TBL_HISTORY_DTIME')            OR define('TBL_HISTORY_DTIME',      'del_time');
defined('TBL_HISTORY_DID')            OR define('TBL_HISTORY_DID',      'did');

defined('TBL_LIKE_ID')            OR define('TBL_LIKE_ID',      'no');
defined('TBL_LIKE_UID')            OR define('TBL_LIKE_UID',      'uid');
defined('TBL_LIKE_MID')            OR define('TBL_LIKE_MID',      'mid');
defined('TBL_LIKE_DID')            OR define('TBL_LIKE_DID',      'did');

defined('TBL_FEED_NO')            OR define('TBL_FEED_NO',      'no');
defined('TBL_FEED_WHO')            OR define('TBL_FEED_WHO',      'who_name');
defined('TBL_FEED_WHOM')            OR define('TBL_FEED_WHOM',      'whom_name');
defined('TBL_FEED_TYPE')            OR define('TBL_FEED_TYPE',      'type');
defined('TBL_FEED_TAG')            OR define('TBL_FEED_TAG',      'tag');
defined('TBL_FEED_WHO_ID')            OR define('TBL_FEED_WHO_ID',      'who_id');
defined('TBL_FEED_WHO_BIO')            OR define('TBL_FEED_WHO_BIO',      'who_bio');
defined('TBL_FEED_WHOM_ID')            OR define('TBL_FEED_WHOM_ID',      'whom_id');
defined('TBL_FEED_WHOM_BIO')            OR define('TBL_FEED_WHOM_BIO',      'whom_bio');
defined('TBL_FEED_PHOTO')            OR define('TBL_FEED_PHOTO',      'photo');
defined('TBL_FEED_TIME')            OR define('TBL_FEED_TIME',      'time');


defined('TBL_NAME_USER')          OR define('TBL_NAME_USER',     'tbl_user');
defined('TBL_NAME_CHAT')          OR define('TBL_NAME_CHAT',     'tbl_chat');
defined('TBL_NAME_OPTION')          OR define('TBL_NAME_OPTION',   'tbl_option');
defined('TBL_NAME_QUESTION')          OR define('TBL_NAME_QUESTION',     'tbl_que_detail');
defined('TBL_NAME_REVIEW')          OR define('TBL_NAME_REVIEW',     'tbl_review');
defined('TBL_NAME_COMMENT')          OR define('TBL_NAME_COMMENT',     'tbl_comment');
defined('TBL_NAME_BUSINESS')          OR define('TBL_NAME_BUSINESS',     'tbl_business_profile');
defined('TBL_NAME_LINK')          OR define('TBL_NAME_LINK',     'tbl_business_link');
defined('TBL_NAME_SUMMARY')          OR define('TBL_NAME_SUMMARY',     'tbl_summary');
defined('TBL_NAME_INVITE')          OR define('TBL_NAME_INVITE',     'tbl_invite_code');
defined('TBL_NAME_GROUP')          OR define('TBL_NAME_GROUP',     'tbl_group');
defined('TBL_NAME_LEADER')          OR define('TBL_NAME_LEADER',     'tbl_leader');
defined('TBL_NAME_CHAT_HISTORY')          OR define('TBL_NAME_CHAT_HISTORY',     'tbl_chat_history');
defined('TBL_NAME_LIKE')          OR define('TBL_NAME_LIKE',     'tbl_like');
defined('TBL_NAME_ACTION')          OR define('TBL_NAME_ACTION',     'tbl_action_feed');



defined('USER_LOGIN_SUCCESS')     OR define('USER_LOGIN_SUCCESS', 1);
defined('USER_LOGIN_404')         OR define('USER_LOGIN_404', 0);
defined('USER_LOGIN_DELETE')      OR define('USER_LOGIN_DELETE', 2);
defined('USER_LOGIN_PWD')         OR define('USER_LOGIN_PWD', 3);