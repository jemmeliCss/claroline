<?php
/* $Id$ */

/**
 * Last translation by: Siu Sun <siusun@best-view.net>
 * Follow by the original translation of Taiyen Hung �x����<yen789@pchome.com.tw>
 */

$charset = 'big5';
$text_dir = 'ltr';
$left_font_family = 'verdana, arial, helvetica, geneva, sans-serif';
$right_font_family = 'helvetica, sans-serif';
$number_thousands_separator = ',';
$number_decimal_separator = '.';
// shortcuts for Byte, Kilo, Mega, Giga, Tera, Peta, Exa
$byteUnits = array('Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB');

$day_of_week = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');
$month = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
// See http://www.php.net/manual/en/function.strftime.php to define the
// variable below
$datefmt = '%B %d, %Y at %I:%M %p';

$strAPrimaryKey = '�D��w�g�s�W�� %s';
$strAccessDenied = '�ڵ��s��';
$strAction = '����';
$strAddDeleteColumn = '�s�W/��� �����';
$strAddDeleteRow = '�s�W/��� �z��C';
$strAddNewField = '�W�[�s���';
$strAddPriv = '�W�[�s�v��';
$strAddPrivMessage = '�z�w�g���U���o��ϥΪ̼W�[�F�s�v��.';
$strAddSearchConditions = '�W�[�˯����� ("where" �l�y���D��)';
$strAddToIndex = '�s�W &nbsp;%s&nbsp; �կ�����';
$strAddUser = '�s�W�ϥΪ�';
$strAddUserMessage = '�z�w�s�W�F�@�ӷs�ϥΪ�.';
$strAffectedRows = '�v�T�C��: ';
$strAfter = '�b %s ����';
$strAfterInsertBack = '��^';
$strAfterInsertNewInsert = '�s�W�@���O��';
$strAll = '����';
$strAllTableSameWidth = '�H�ۦP�e����ܩҦ���ƪ�?';
$strAlterOrderBy = '�ھ���줺�e�ƧǰO��';
$strAnIndex = '���ޤw�g�s�W�� %s';
$strAnalyzeTable = '���R��ƪ�';
$strAnd = '�P';
$strAny = '����';
$strAnyColumn = '�������';
$strAnyDatabase = '�����Ʈw';
$strAnyHost = '����D��';
$strAnyTable = '�����ƪ�';
$strAnyUser = '����ϥΪ�';
$strAscending = '���W';
$strAtBeginningOfTable = '���ƪ�}�Y';
$strAtEndOfTable = '���ƪ����';
$strAttr = '�ݩ�';

$strBack = '�^�W�@��';
$strBeginCut = '�}�l �Ũ�';
$strBeginRaw = '�}�l ��l���';
$strBinary = '�G�i��X';
$strBinaryDoNotEdit = '�G�i��X - ����s��';
$strBookmarkDeleted = '���Ҥw�g�R��.';
$strBookmarkLabel = '���ҦW��';
$strBookmarkQuery = 'SQL �y�k����';
$strBookmarkThis = '�N�� SQL �y�k�[�J����';
$strBookmarkView = '�d��';
$strBrowse = '�s��';
$strBzip = '"bzipped"';

$strCantLoadMySQL = '������J MySQL �Ҳ�,<br />���ˬd PHP ���պA�]�w';
$strCantLoadRecodeIconv = '����Ū�� iconv �έ��s�s�X�{���ӧ@��r�s�X�ഫ, �г]�w php �ӱҰʳo�ǼҲթΨ��� phpMyAdmin �ϥΤ�r�s�X�ഫ�\��.';
$strCantRenameIdxToPrimary = '�L�k�N���ާ�W�� PRIMARY!';
$strCantUseRecodeIconv = '���s�X�Ҳ�Ū����,����ϥ� iconv �B libiconv �� recode_string �\��. ���ˬd�z�� php �]�w.';
$strCardinality = '�էO';
$strCarriage = '�k��: \\r';
$strChange = '�ק�';
$strChangeDisplay = '�����ܤ����';
$strChangePassword = '���K�X';
$strCharsetOfFile = '�r�����ɮ�:';
$strCheckAll = '����';
$strCheckDbPriv = '�ˬd��Ʈw�v��';
$strCheckTable = '�ˬd��ƪ�';
$strChoosePage = '�п�ܻݭn�s�誺���X';
$strColComFeat = '���������';
$strColumn = '���';
$strColumnNames = '���W��';
$strComments = '����';
$strCompleteInserts = '�ϥΧ���s�W���O';
$strCompression = '���Y';
$strConfigFileError = 'phpMyAdmin ����Ū���z���]�w��! �o�i��O�]�� php ���y�k�W�����~�� php �������ɮצӦ�.<br />�й��ժ������U�U�誺�s���}�Ҩìd�� php �����~�H��. �q�`�����~���Ӧ۬Y�B�|�F�޸��Τ��O.<br />�p�G���U�s����X�{�ťխ�, �Y�N��S��������D.';
$strConfigureTableCoord = '�г]�w��� %s ��������';
$strConfirm = '�z�T�w�n�o�˰��H';
$strCookiesRequired = 'Cookies �����Ұʤ~��n�J.';
$strCopyTable = '�ƻs��ƪ��G (�榡�� ��Ʈw�W��<b>.</b>��ƪ�W��):';
$strCopyTableOK = '�w�g�N��ƪ� %s �ƻs�� %s.';
$strCreate = '�إ�';
$strCreateIndex = '�s�W &nbsp;%s&nbsp; �կ�����';
$strCreateIndexTopic = '�s�W�@�կ���';
$strCreateNewDatabase = '�إ߷s��Ʈw';
$strCreateNewTable = '�إ߷s��ƪ���Ʈw %s';
$strCreatePage = '�إ߷s�@��';
$strCreatePdfFeat = '�إ� PDF';
$strCriteria = '�z��';

$strData = '���';
$strDataDict = '�ƾڦr��';
$strDataOnly = '�u�����';
$strDatabase = '��Ʈw';
$strDatabaseHasBeenDropped = '��Ʈw %s �w�Q�R��';
$strDatabaseWildcard = '��Ʈw (���\�ϥθU�Φr��):';
$strDatabases = '��Ʈw';
$strDatabasesStats = '��Ʈw�έp';
$strDefault = '�w�]��';
$strDelete = '�R��';
$strDeleteFailed = '�R������!';
$strDeleteUserMessage = '�z�w�g�N�Τ� %s �R��.';
$strDeleted = '�O���w�Q�R��';
$strDeletedRows = '�w�R�����:';
$strDescending = '����';
$strDisabled = '���Ұ�';
$strDisplay = '���';
$strDisplayFeat = '�\�����';
$strDisplayOrder = '��ܦ���';
$strDisplayPDF = '��� PDF ���n';
$strDoAQuery = '�H�d�Ҭd�� (�U�Φr�� : "%")';
$strDoYouReally = '�z�T�w�n ';
$strDocu = '�������';
$strDrop = '�R��';
$strDropDB = '�R����Ʈw %s';
$strDropTable = '�R����ƪ�';
$strDumpXRows = '�ƥ� %s ��, �� %s ��}�l.';
$strDumpingData = '�C�X�H�U��Ʈw���ƾڡG';
$strDynamic = '�ʺA';

$strEdit = '�s��';
$strEditPDFPages = '�s�� PDF ���X';
$strEditPrivileges = '�s���v��';
$strEffective = '���';
$strEmpty = '�M��';
$strEmptyResultSet = 'MySQL �Ǧ^���d�ߵ��G���� (��]�i�ର�G�S�����ŦX���󪺰O��)';
$strEnabled = '�Ұ�';
$strEnd = '�̫�@��';
$strEndCut = '���� �Ũ�';
$strEndRaw = '���� ��l���';
$strEnglishPrivileges = '�`�N: MySQL �v���W�ٷ|�H�^�y���';
$strError = '���~';
$strExplain = '���� SQL';
$strExport = '��X';
$strExportToXML = '��X�� XML �榡';
$strExtendedInserts = '�����s�W�Ҧ�';
$strExtra = '���[';

$strField = '���';
$strFieldHasBeenDropped = '��ƪ� %s �w�Q�R��';
$strFields = '���';
$strFieldsEmpty = ' ����`�ƬO�Ū�! ';
$strFieldsEnclosedBy = '�u���v�ϥΦr���G';
$strFieldsEscapedBy = '�uESCAPE�v�ϥΦr���G';
$strFieldsTerminatedBy = '�u�����j�v�ϥΦr���G';
$strFixed = '�T�w';
$strFlushTable = '�j����s��ƪ� ("FLUSH")';
$strFormEmpty = '��椺�|��@�Ǹ��!';
$strFormat = '�榡';
$strFullText = '��ܧ����r';
$strFunction = '���';

$strGenBy = '�إ�';
$strGenTime = '�إߤ��';
$strGeneralRelationFeat = '�@�����p�\��';
$strGo = '����';
$strGrants = 'Grants'; //should expressed in English
$strGzip = '"gzipped"';

$strHasBeenAltered = '�w�g�ק�';
$strHasBeenCreated = '�w�g�إ�';
$strHaveToShow = '�z�ݭn��̤ܳ���ܤ@�����';
$strHome = '�D�ؿ�';
$strHomepageOfficial = 'phpMyAdmin �x�����';
$strHomepageSourceforge = 'phpMyAdmin �U������';
$strHost = '�D��';
$strHostEmpty = '�п�J�D���W��!';

$strIdxFulltext = '�����˯�';
$strIfYouWish = '�p�G�z�n���w��ƶפJ�����A�п�J�γr���j�}�����W��';
$strIgnore = '����';
$strInUse = '�ϥΤ�';
$strIndex = '����';
$strIndexHasBeenDropped = '���� %s �w�Q�R��';
$strIndexName = '���ަW��&nbsp;:';
$strIndexType = '��������&nbsp;:';
$strIndexes = '����';
$strInsecureMySQL = '�]�w�ɤ������]�w (root�n�J�ΨS���K�X) �P�w�]�� MySQL �v����f�ۦP�C MySQL ���A���b�o�w�]���]�w�B�檺�ܷ|�ܮe���Q�J�I�A�z����靈���]�w�h����w���|�}�C';
$strInsert = '�s�W';
$strInsertAsNewRow = '�x�s���s�O��';
$strInsertNewRow = '�s�W�@���O��';
$strInsertTextfiles = '�N��r�ɸ�ƶפJ��ƪ�';
$strInsertedRows = '�s�W�C��:';
$strInstructions = '���O';
$strInvalidName = '"%s" �O�@�ӫO�d�r,�z����N�O�d�r�ϥά� ��Ʈw/��ƪ�/��� �W��.';

$strKeepPass = '�Ф��n���K�X';
$strKeyname = '��W';
$strKill = 'Kill'; //should expressed in English

$strLength = '����';
$strLengthSet = '����/���X*';
$strLimitNumRows = '���O��/�C��';
$strLineFeed = '����: \\n';
$strLines = '���';
$strLinesTerminatedBy = '�u�U�@��v�ϥΦr���G';
$strLinkNotFound = '�䤣��s��';
$strLinksTo = '�s����';
$strLocationTextfile = '��r�ɮת���m';
$strLogPassword = '�K�X:';
$strLogUsername = '�n�J�W��:';
$strLogin = '�n�J';
$strLogout = '�n�X�t��';

$strMissingBracket = '�䤣��A��';
$strModifications = '�ק�w�x�s';
$strModify = '�ק�';
$strModifyIndexTopic = '�ק����';
$strMoveTable = '���ʸ�ƪ��G(�榡�� ��Ʈw�W��<b>.</b>��ƪ�W��)';
$strMoveTableOK = '��ƪ� %s �w�g���ʨ� %s.';
$strMySQLCharset = 'MySQL ��r�s�X';
$strMySQLReloaded = 'MySQL ���s���J����';
$strMySQLSaid = 'MySQL �Ǧ^�G ';
$strMySQLServerProcess = 'MySQL ���� %pma_s1% �b %pma_s2% ����A�n�J�̬� %pma_s3%';
$strMySQLShowProcess = '��ܵ{�� (Process)';
$strMySQLShowStatus = '��� MySQL ���檬�A';
$strMySQLShowVars = '��� MySQL �t���ܼ�';

$strName = '�W��';
$strNext = '�U�@��';
$strNo = ' �_ ';
$strNoDatabases = '�S����Ʈw';
$strNoDescription = '�S������';
$strNoDropDatabases = '"DROP DATABASE" ���O�w�g����.';
$strNoExplain = '���L���� SQL';
$strNoFrames = 'phpMyAdmin �����A�X�ϥΦb�䴩<b>����</b>���s����.';
$strNoIndex = '�S���w�w�q������!';
$strNoIndexPartsDefined = '�������޸���٥��w�q!';
$strNoModification = '�S���ܧ�';
$strNoPassword = '���αK�X';
$strNoPhp = '���� PHP �{���X';
$strNoPrivileges = '�S���v��';
$strNoQuery = '�S�� SQL �y�k!';
$strNoRights = '�z�{�b�S���������v��!';
$strNoTablesFound = '��Ʈw���S����ƪ�';
$strNoUsersFound = '�䤣��ϥΪ�';
$strNoValidateSQL = '���L�ˬd SQL';
$strNone = '���A��';
$strNotNumber = '�o���O�@�ӼƦr!';
$strNotOK = '����T�w';
$strNotSet = '<b>%s</b> ��ƪ�䤣����٥��b %s �]�w';
$strNotValidNumber = '���O���Ī��C��!';
$strNull = 'Null'; //should expressed in English
$strNumSearchResultsInTable = '%s ����ƲŦX - ���ƪ� <i>%s</i>';
$strNumSearchResultsTotal = '<b>�`�p:</b> <i>%s</i> ����ƲŦX';

$strOK = '�T�w';
$strOftenQuotation = '�̱`�Ϊ��O�޸��A�u�D�����v��ܥu�� char �M varchar ���|�Q�]�A�_��';
$strOperations = '�޲z';
$strOptimizeTable = '�̨ΤƸ�ƪ�';
$strOptionalControls = '�D���n�ﶵ�A�Ψ�Ū�g�S��r��';
$strOptionally = '�D����';
$strOptions = '�ﶵ';
$strOr = '��';
$strOverhead = '�h�l';

$strPHP40203 = '�z���ϥ� PHP ���� 4.2.3, �o�������@�����r�`�r�����Y�����~(mbstring). �аѾ\ PHP ���γ��i�s�� 19404. phpMyAdmin �ä���ĳ�ϥγo�Ӫ����� PHP .';
$strPHPVersion = 'PHP ����';
$strPageNumber = '���X:';
$strPartialText = '��ܳ�����r';
$strPassword = '�K�X';
$strPasswordEmpty = '�п�J�K�X!';
$strPasswordNotSame = '�ĤG����J���K�X���P!';
$strPdfDbSchema = '"%s" ��Ʈw���n - �� %s ��';
$strPdfInvalidPageNum = 'PDF ���X�S���]�w!';
$strPdfInvalidTblName = '��ƪ� "%s" ���s�b!';
$strPdfNoTables = '�S����ƪ�';
$strPhp = '�إ� PHP �{���X';
$strPmaDocumentation = 'phpMyAdmin �������';
$strPmaUriError = ' �����]�w <tt>$cfg[\'PmaAbsoluteUri\']</tt> �b�]�w�ɤ�!';
$strPos1 = '�Ĥ@��';
$strPrevious = '�e�@��';
$strPrimary = '�D��';
$strPrimaryKey = '�D��';
$strPrimaryKeyHasBeenDropped = '�D��w�Q�R��';
$strPrimaryKeyName = '�D�䪺�W�٥����٬� PRIMARY!';
$strPrimaryKeyWarning = '("PRIMARY" <b>����</b>�O�D�䪺�W�٥H�άO<b>�ߤ@</b>�@�եD��!)';
$strPrint = '�C�L';
$strPrintView = '�C�L�˵�';
$strPrivDescMaxConnections = 'Limits the number of new connections the user may open per hour.';
$strPrivDescMaxQuestions = 'Limits the number of queries the user may send to the server per hour.';
$strPrivDescMaxUpdates = 'Limits the number of commands that change any table or database the user may execute per hour.';
$strPrivileges = '�v��';
$strProperties = '�ݩ�';
$strPutColNames = '�N���W�٩�b���C';

$strQBE = '�̽d�Ҭd�� (QBE)';
$strQBEDel = '����';
$strQBEIns = '�s�W';
$strQueryOnDb = '�b��Ʈw <b>%s</b> ���� SQL �y�k:';
$strQueryStatistics = '<b>Query statistics</b>: Since its startup, %s queries have been sent to the server.';

$strReType = '�T�{�K�X';
$strRecords = '�O��';
$strReferentialIntegrity = '�ˬd���ܧ����:';
$strRelationNotWorking = '���p��ƪ����[�\�ॼ��Ұ�, %s�Ы���%s �d�X���D��].';
$strRelationView = '���p�˵�';
$strReloadFailed = '���s���JMySQL����';
$strReloadMySQL = '���s���J MySQL';
$strRememberReload = '�аO�ۭ��s�Ұʦ��A��.';
$strRenameTable = '�N��ƪ��W��';
$strRenameTableOK = '�w�g�N��ƪ� %s ��W�� %s';
$strRepairTable = '�״_��ƪ�';
$strReplace = '���N';
$strReplaceTable = '�H�ɮר��N��ƪ���';
$strReset = '���m';
$strRevoke = '����';
$strRevokeGrant = '���� Grant �v��';
$strRevokeGrantMessage = '�z�w�����o��ϥΪ̪� Grant �v��: %s';
$strRevokeMessage = '�z�w�����o��ϥΪ̪��v��: %s';
$strRevokePriv = '�����v��';
$strRowLength = '��ƦC����';
$strRowSize = '��ƦC�j�p';
$strRows = '��ƦC�C��';
$strRowsFrom = '���O���A�}�l�C��:';
$strRowsModeHorizontal = '����';
$strRowsModeOptions = '��ܬ� %s �覡 �� �C�j %s �������W';
$strRowsModeVertical = '����';
$strRowsStatistic = '��ƦC�έp�ƭ�';
$strRunQuery = '����y�k';
$strRunSQLQuery = '�b��Ʈw %s ����H�U���O';
$strRunning = '�b %s ����';

$strSQL = 'SQL'; // should express in english
$strSQLParserBugMessage = '�o�i��O�z���F SQL ���R�{�����@�ǵ{�����~�A�вӤ߬d�ݱz���y�k�A�ˬd�@�U�޸��O���T�ΨS����|�A��L�i��X������]�i��Ӧ۱z�W���ɮ׮ɦb�޸��~���a��ϥΤF�G�i��X�C�z�i�H���զb MySQL �R�O�C��������ӻy�k�C�p MySQL ���A���o�X���~�H���A�o�i�����U�z�h��X���D�Ҧb�C�p�z���M����ѨM���D�A�Φb���R�{���X�{���~�A���b�R�O�C�Ҧ��ॿ�`����A�бN�ӥy�X�{���~�� SQL �y�k��X�A�ñN�H�U��"�Ũ�"�����@�P��������:';
$strSQLParserUserError = '�i��O�z�� SQL �y�k�X�{���~�A�p MySQL ���A���o�X���~�H���A�o�i�����U�z�h��X���D�Ҧb�C';
$strSQLQuery = 'SQL �y�k';
$strSQLResult = 'SQL �d�ߵ��G';
$strSQPBugInvalidIdentifer = '�L�Ī��ѧO�X (Invalid Identifer)';
$strSQPBugUnclosedQuote = '���������޸� (Unclosed quote)';
$strSQPBugUnknownPunctuation = '�����������I�Ÿ� (Unknown Punctuation String)';
$strSave = '�x�s';
$strScaleFactorSmall = '��ҭ��ƤӲ�, �L�k�N�Ϫ��b�@����';
$strSearch = '�j��';
$strSearchFormTitle = '�j����Ʈw';
$strSearchInTables = '��H�U��ƪ�:';
$strSearchNeedle = '�M�䤧��r�μƭ� (�U�Φr��: "%"):';
$strSearchOption1 = '����@�դ�r';
$strSearchOption2 = '�Ҧ���r';
$strSearchOption3 = '������y';
$strSearchOption4 = '�H�W�h��ܪk (regular expression) �j��';
$strSearchResultsFor = '�j�� "<i>%s</i>" �����G %s:';
$strSearchType = '�M��:';
$strSelect = '���';
$strSelectADb = '�п�ܸ�Ʈw';
$strSelectAll = '����';
$strSelectFields = '������ (�ܤ֤@��)';
$strSelectNumRows = '�d�ߤ�';
$strSelectTables = '��ܸ�ƪ�';
$strSend = '�U���x�s';
$strServer = '���A�� %s';
$strServerChoice = '��ܦ��A��';
$strServerTrafficNotes = '<b>Server traffic</b>: These tables show the network traffic statistics of this MySQL server since its startup.';
$strServerVersion = '���A������';
$strSetEnumVal = '�p���榡�O "enum" �� "set", �ШϥΥH�U���榡��J: \'a\',\'b\',\'c\'...<br />�p�b�ƭȤW�ݭn��J�ϱ׽u (\) �γ�޸� (\') , �ЦA�[�W�ϱ׽u (�Ҧp \'\\\\xyz\' or \'a\\\'b\').';
$strShow = '���';
$strShowAll = '��ܥ���';
$strShowColor = '����C��';
$strShowCols = '�����';
$strShowGrid = '��ܮخ�';
$strShowPHPInfo = '��� PHP ��T';
$strShowTableDimension = '��ܪ��j�p';
$strShowTables = '��ܸ�ƪ�';
$strShowThisQuery = '���s��� SQL �y�k ';
$strShowingRecords = '��ܰO��';
$strSingly = '(�u�|�Ƨǲ{�ɪ��O��)';
$strSize = '�j�p';
$strSort = '�Ƨ�';
$strSpaceUsage = '�w�ϥΪŶ�';
$strSplitWordsWithSpace = '�C�դ�r�H�Ů� (" ") ���j.';
$strStatement = '�ԭz';
$strStrucCSV = 'CSV ���';
$strStrucData = '���c�P���';
$strStrucDrop = '�W�[ \'drop table\'';
$strStrucExcelCSV = 'Ms Excel �� CSV �榡';
$strStrucOnly = '�u�����c';
$strStructPropose = '���R��ƪ��c';
$strStructure = '���c';
$strSubmit = '�e�X';
$strSuccess = '�z��SQL�y�k�w���Q����';
$strSum = '�`�p';

$strTable = '��ƪ�';
$strTableComments = '��ƪ���Ѥ�r';
$strTableEmpty = '�п�J��ƪ�W��!';
$strTableHasBeenDropped = '��ƪ� %s �w�Q�R��';
$strTableHasBeenEmptied = '��ƪ� %s �w�Q�M��';
$strTableHasBeenFlushed = '��ƪ� %s �w�Q�j����s';
$strTableMaintenance = '��ƪ���@';
$strTableStructure = '��ƪ�榡�G';
$strTableType = '��ƪ�����';
$strTables = '%s ��ƪ�';
$strTextAreaLength = ' �ѩ���׭���<br /> ����줣��s�� ';
$strTheContent = '�ɮפ��e�w�g�פJ��ƪ�';
$strTheContents = '�ɮפ��e�N�|���N��w����ƪ��㦳�ۦP�D��ΰߤ@�䪺�O��';
$strTheTerminator = '���j��쪺�r��';
$strTotal = '�`�p';
$strType = '���A';

$strUncheckAll = '��������';
$strUnique = '�ߤ@';
$strUnselectAll = '��������';
$strUpdatePrivMessage = '�z�w�g��s�F %s ���v��.';
$strUpdateProfile = '��s���:';
$strUpdateProfileMessage = '��Ƥv�g��s.';
$strUpdateQuery = '��s�y�k';
$strUsage = '�ϥ�';
$strUseBackquotes = '�Цb��ƪ�����ϥΤ޸�';
$strUseTables = '�ϥθ�ƪ�';
$strUser = '�ϥΪ�';
$strUserEmpty = '�п�J�ϥΪ̦W��!';
$strUserName = '�ϥΪ̦W��';
$strUsers = '�ϥΪ�';

$strValidateSQL = '�ˬd SQL';
$strValidatorError = 'SQL ���R�{������ҰʡA���ˬd�O�_�w�N %s���%s ���� PHP �ɮצw�ˡC';
$strValue = '��';
$strViewDump = '�˵���ƪ��ƥ����n (dump schema)';
$strViewDumpDB = '�˵���Ʈw���ƥ����n (dump schema)';

$strWebServerUploadDirectory = 'Web ���A���W���ؿ�';
$strWebServerUploadDirectoryError = '�]�w���W���ؿ����~�A����ϥ�';
$strWelcome = '�w��ϥ� %s';
$strWithChecked = '��ܪ���ƪ�G';
$strWrongUser = '���~���ϥΪ̦W�٩αK�X�A�ڵ��s��';

$strYes = ' �O ';

$strZip = '"zipped"';

// To translate
$timespanfmt = '%s days, %s hours, %s minutes and %s seconds'; //to translate

$strAbortedClients = 'Aborted'; //to translate
$strAdministration = 'Administration'; //to translate

$strBzError = 'phpMyAdmin was unable to compress the dump because of a broken Bz2 extension in this php version. It is strongly recommended to set the <code>$cfg[\'BZipDump\']</code> directive in your phpMyAdmin configuration file to <code>FALSE</code>. If you want to use the Bz2 compression features, you should upgrade to a later php version. See php bug report %s for details.'; //to translate

$strCannotLogin = 'Cannot login to MySQL server';  //to translate
$strCommand = 'Command'; //to translate
$strConnections = 'Connections'; //to translate
$strCouldNotKill = 'phpMyAdmin was unable to kill thread %s. It probably has already been closed.'; //to translate

$strDeleteAndFlush = 'Delete the users and reload the privileges afterwards.'; //to translate
$strDeleteAndFlushDescr = 'This is the cleanest way, but reloading the privileges may take a while.'; //to translate
$strDeleting = 'Deleting %s'; //to translate

$strFailedAttempts = 'Failed attempts'; //to translate
$strFlushPrivilegesNote = 'Note: phpMyAdmin gets the users\' privileges directly from MySQL\'s privilege tables. The content of this tables may differ from the privileges the server uses if manual changes have made to it. In this case, you should %sreload the privileges%s before you continue.'; //to translate

$strGlobalPrivileges = 'Global privileges'; //to translate
$strGlobalValue = 'Global value'; //to translate
$strGrantOption = 'Grant'; //to translate

$strId = 'ID'; //to translate
$strImportDocSQL = 'Import docSQL Files';  //to translate

$strJustDelete = 'Just delete the users from the privilege tables.'; //to translate
$strJustDeleteDescr = 'The &quot;deleted&quot; users will still be able to access the server as usual until the privileges are reloaded.'; //to translate

$strLaTeX = 'LaTeX';  //to translate
$strLandscape = 'Landscape';  //to translate

$strMoreStatusVars = 'More status variables'; //to translate

$strNumTables = 'Tables'; //to translate


$strPasswordChanged = 'The Password for %s was changed successfully.'; // to translate
$strPerHour = 'per hour'; //to translate
$strPortrait = 'Portrait';  //to translate
$strPrivDescAllPrivileges = 'Includes all privileges except GRANT.'; //to translate
$strPrivDescAlter = 'Allows altering the structure of existing tables.'; //to translate
$strPrivDescCreateDb = 'Allows creating new databases and tables.'; //to translate
$strPrivDescCreateTbl = 'Allows creating new tables.'; //to translate
$strPrivDescCreateTmpTable = 'Allows creating temporary tables.'; //to translate
$strPrivDescDelete = 'Allows deleting data.'; //to translate
$strPrivDescDropDb = 'Allows dropping databases and tables.'; //to translate
$strPrivDescDropTbl = 'Allows dropping tables.'; //to translate
$strPrivDescExecute = 'Allows running stored procedures; Has no effect in this MySQL version.'; //to translate
$strPrivDescFile = 'Allows importing data from and exporting data into files.'; //to translate
$strPrivDescGrant = 'Allows adding users and privileges without reloading the privilege tables.'; //to translate
$strPrivDescIndex = 'Allows creating and dropping indexes.'; //to translate
$strPrivDescInsert = 'Allows inserting and replacing data.'; //to translate
$strPrivDescLockTables = 'Allows locking tables for the current thread.'; //to translate
$strPrivDescProcess3 = 'Allows killing processes of other users.'; //to translate
$strPrivDescProcess4 = 'Allows viewing the complete queries in the process list.'; //to translate
$strPrivDescReferences = 'Has no effect in this MySQL version.'; //to translate
$strPrivDescReload = 'Allows reloading server settings and flushing the server\'s caches.'; //to translate
$strPrivDescReplClient = 'Gives the right to the user to ask where the slaves / masters are.'; //to translate
$strPrivDescReplSlave = 'Needed for the replication slaves.'; //to translate
$strPrivDescSelect = 'Allows reading data.'; //to translate
$strPrivDescShowDb = 'Gives access to the complete list of databases.'; //to translate
$strPrivDescShutdown = 'Allows shutting down the server.'; //to translate
$strPrivDescSuper = 'Allows connectiong, even if maximum number of connections is reached; Required for most administrative operations like setting global variables or killing threads of other users.'; //to translate
$strPrivDescUpdate = 'Allows changing data.'; //to translate
$strPrivDescUsage = 'No privileges.'; //to translate
$strPrivilegesReloaded = 'The privileges were reloaded successfully.'; //to translate
$strProcesslist = 'Process list'; //to translate

$strQueryType = 'Query type'; //to translate

$strReceived = 'Received'; //to translate
$strRelationalSchema = 'Relational schema';  //to translate
$strReloadingThePrivileges = 'Reloading the privileges'; //to translate
$strRemoveSelectedUsers = 'Remove selected users'; //to translate
$strResourceLimits = 'Resource limits'; //to translate
$strRevokeAndDelete = 'Revoke all active privileges from the users and delete them afterwards.'; //to translate
$strRevokeAndDeleteDescr = 'The users will still have the USAGE privilege until the privileges are reloaded.'; //to translate

$strSent = 'Sent'; //to translate
$strServerStatus = 'Runtime Information'; //to translate
$strServerStatusUptime = 'This MySQL server has been running for %s. It started up on %s.'; //to translate
$strServerTabProcesslist = 'Processes'; //to translate
$strServerTabVariables = 'Variables'; //to translate
$strServerVars = 'Server variables and settings'; //to translate
$strSessionValue = 'Session value'; //to translate
$strShowDatadictAs = 'Data Dictionary Format';  //to translate
$strStatus = 'Status'; //to translate

$strTableOfContents = 'Table of contents';  //to translate
$strThreadSuccessfullyKilled = 'Thread %s was successfully killed.'; //to translate
$strTime = 'Time'; //to translate
$strTotalUC = 'Total'; //to translate
$strTraffic = 'Traffic'; //to translate

$strUserOverview = 'User overview'; //to translate
$strUsersDeleted = 'The selected users have been deleted successfully.'; //to translate

$strVar = 'Variable'; //to translate

$strZeroRemovesTheLimit = 'Note: Setting these options to 0 (zero) removes the limit.'; //to translate

$strAddPrivilegesOnDb = 'Add privileges on the following database'; //to translate
$strAddPrivilegesOnTbl = 'Add privileges on the following table'; //to translate
$strColumnPrivileges = 'Column-specific privileges'; //to translate
$strDbPrivileges = 'Database-specific privileges'; //to translate
$strLocalhost = 'Local';
$strLoginInformation = 'Login Information'; //to translate
$strTblPrivileges = 'Table-specific privileges'; //to translate
$strThisHost = 'This Host'; //to translate
$strUserNotFound = 'The selected user was not found in the privilege table.'; //to translate
$strUserAlreadyExists = 'The user %s already exists!'; //to translate
$strUseTextField = 'Use text field'; //to translate

$strNoUsersSelected = 'No users selected.'; //to translate
$strDropUsersDb = 'Drop the databases that have the same names as the users.'; //to translate
$strAddedColumnComment = 'Added comment for column';  //to translate
$strWritingCommentNotPossible = 'Writing of comment not possible';  //to translate
$strAddedColumnRelation = 'Added relation for column';  //to translate
$strWritingRelationNotPossible = 'Writing of relation not possible';  //to translate
$strImportFinished = 'Import finished';  //to translate
$strFileCouldNotBeRead = 'File could not be read';  //to translate
$strIgnoringFile = 'Ignoring file %s';  //to translate
$strThisNotDirectory = 'This was not a directory';  //to translate
$strAbsolutePathToDocSqlDir = 'Please enter the absolute path on webserver to docSQL directory';  //to translate
$strImportFiles = 'Import files';  //to translate
$strDBGModule = 'Module';  //to translate
$strDBGLine = 'Line';  //to translate
$strDBGHits = 'Hits';  //to translate
$strDBGTimePerHitMs = 'Time/Hit, ms';  //to translate
$strDBGTotalTimeMs = 'Total time, ms';  //to translate
$strDBGMinTimeMs = 'Min time, ms';  //to translate
$strDBGMaxTimeMs = 'Max time, ms';  //to translate
$strDBGContextID = 'Context ID';  //to translate
$strDBGContext = 'Context';  //to translate
$strCantLoad = 'cannot load %s extension,<br />please check PHP Configuration';  //to translate
$strDefaultValueHelp = 'For default values, please enter just a single value, without backslash escaping or quotes, using this format: a';  //to translate
$strCheckPrivs = 'Check Privileges';  //to translate
$strCheckPrivsLong = 'Check privileges for database &quot;%s&quot;.';  //to translate
$strDatabasesStatsHeavyTraffic = 'Note: Enabling the Database statistics here might cause heavy traffic between the webserver and the MySQL one.';  //to translate
$strDatabasesStatsDisable = 'Disable Statistics';  //to translate
$strDatabasesStatsEnable = 'Enable Statistics';  //to translate
$strJumpToDB = 'Jump to database &quot;%s&quot;.';  //to translate
$strDropSelectedDatabases = 'Drop Selected Databases';  //to translate
$strNoDatabasesSelected = 'No databases selected.';  //to translate
$strDatabasesDropped = '%s databases have been dropped successfully.';  //to translate
$strGlobal = 'global';  //to translate
$strDbSpecific = 'database-specific';  //to translate
$strUsersHavingAccessToDb = 'Users having access to &quot;%s&quot;';  //to translate
$strChangeCopyUser = 'Change Login Information / Copy User';  //to translate
$strChangeCopyMode = 'Create a new user with the same privileges and ...';  //to translate
$strChangeCopyModeCopy = '... keep the old one.';  //to translate
$strChangeCopyModeJustDelete = ' ... delete the old one from the user tables.';  //to translate
$strChangeCopyModeRevoke = ' ... revoke all active privileges from the old one and delete it afterwards.';  //to translate
$strChangeCopyModeDeleteAndReload = ' ... delete the old one from the user tables and reload the privileges afterwards.';  //to translate
$strWildcard = 'wildcard';  //to translate
?>
