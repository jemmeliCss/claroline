<?php
/* $Id$ */

/**
 * Original translation to Arabic by Fisal <fisal77 at hotmail.com>
 * Update by Tarik kallida <kallida at caramail.com>
 * Final Update on Februray 4, 2002 by Ossama Khayat <ossamak at nht.com.kw>
 */

$charset = 'windows-1256';
$text_dir = 'rtl'; // ('ltr' for left to right, 'rtl' for right to left)
$left_font_family = 'Tahoma, verdana, arial, helvetica, sans-serif';
$right_font_family = '"Windows UI", Tahoma, verdana, arial, helvetica, sans-serif';
$number_thousands_separator = ',';
$number_decimal_separator = '.';
// shortcuts for Byte, Kilo, Mega, Giga, Tera, Peta, Exa
$byteUnits = array('����', '��������', '��������', '��������');

$day_of_week = array('�����', '�������', '��������', '��������', '������', '������', '�����');
$month = array('�����', '������', '����', '�����', '����', '�����', '�����', '�����', '������', '������', '������', '������');
// See http://www.php.net/manual/en/function.strftime.php to define the
// variable below
$datefmt = '%d %B %Y ������ %H:%M';

$strAccessDenied = '��� �����';
$strAction = '�������';
$strAddDeleteColumn = '�����/��� ���� ���';
$strAddDeleteRow = '�����/��� �� ���';
$strAddNewField = '����� ��� ����';
$strAddPriv = '����� ������ ����';
$strAddPrivMessage = '��� ���� ������ ����.';
$strAddSearchConditions = '��� ���� ����� (��� �� ������ "where" clause):';
$strAddToIndex = '����� ����� &nbsp;%s&nbsp;��(���)';
$strAddUser = '��� ������ ����';
$strAddUserMessage = '��� ���� ������ ����.';
$strAffectedRows = '���� �����:';
$strAfter = '��� %s';
$strAfterInsertBack = '������ ��� ������ �������';
$strAfterInsertNewInsert = '����� ����� ����';
$strAll = '����';
$strAlterOrderBy = '����� ����� ������ ��';
$strAnalyzeTable = '����� ������';
$strAnd = '�';
$strAnIndex = '��� ����� ������ �� %s';
$strAny = '��';
$strAnyColumn = '�� ����';
$strAnyDatabase = '�� ����� ������';
$strAnyHost = '�� ����';
$strAnyTable = '�� ����';
$strAnyUser = '�� ������';
$strAPrimaryKey = '��� ����� ������� ������� �� %s';
$strAscending = '��������';
$strAtBeginningOfTable = '�� ����� ������';
$strAtEndOfTable = '�� ����� ������';
$strAttr = '������';

$strBack = '����';
$strBinary = '�����';
$strBinaryDoNotEdit = '����� - �������';
$strBookmarkDeleted = '��� ����� ������� ��������.';
$strBookmarkLabel = '�����';
$strBookmarkQuery = '����� ������ SQL-�������';
$strBookmarkThis = '���� ����� ������ SQL-�������';
$strBookmarkView = '��� ���';
$strBrowse = '�������';
$strBzip = '"bzipped"';

$strCantLoadMySQL = '������ ����� ������ MySQL,<br />������ ��� ������� PHP.';
$strCantRenameIdxToPrimary = '������ ����� ��� ������ ��� �������!';
$strCardinality = 'Cardinality';
$strCarriage = '����� �������: \\r';
$strChange = '�����';
$strChangePassword = '����� ���� ����';
$strCheckAll = '���� ����';
$strCheckDbPriv = '��� ������ ����� ��������';
$strCheckTable = '������ �� ������';
$strColumn = '����';
$strColumnNames = '��� ������';
$strCompleteInserts = '������� ��� �����';
$strConfirm = '�� ���� ���� �� ���� ��߿';
$strCookiesRequired = '��� ����� ��� ������� �� ��� �������.';
$strCopyTable = '��� ������ ���';
$strCopyTableOK = '������ %s ��� �� ���� ��� %s.';
$strCreate = '�����';
$strCreateIndex = '����� ����� ���&nbsp;%s&nbsp;����';
$strCreateIndexTopic = '����� ����� �����';
$strCreateNewDatabase = '����� ����� ������ �����';
$strCreateNewTable = '����� ���� ���� �� ����� �������� %s';
$strCriteria = '��������';

$strData = '������';
$strDatabase = '����� �������� ';
$strDatabaseHasBeenDropped = '����� ������ %s ������.';
$strDatabases = '����� ������';
$strDatabasesStats = '�������� ����� ��������';
$strDatabaseWildcard = '����� ������:';
$strDataOnly = '������ ���';
$strDefault = '�������';
$strDelete = '���';
$strDeleted = '��� �� ��� ����';
$strDeletedRows = '������ ��������:';
$strDeleteFailed = '����� ����!';
$strDeleteUserMessage = '��� ���� �������� %s.';
$strDescending = '��������';
$strDisplay = '���';
$strDisplayOrder = '����� �����:';
$strDoAQuery = '���� "������� ������ ������" (wildcard: "%")';
$strDocu = '������� �������';
$strDoYouReally = '�� ���� ���� �����';
$strDrop = '���';
$strDropDB = '��� ����� ������ %s';
$strDropTable = '��� ����';
$strDumpingData = '����� �� ������� ������ ������';
$strDynamic = '��������';

$strEdit = '�����';
$strEditPrivileges = '����� ����������';
$strEffective = '����';
$strEmpty = '����� �����';
$strEmptyResultSet = 'MySQL ��� ������ ����� ����� ����� (�����. �� ����).';
$strEnd = '�����';
$strEnglishPrivileges = ' ������: ��� �������� ��MySQL ���� ������ ������ ���������� ��� ';
$strError = '���';
$strExtendedInserts = '����� ����';
$strExtra = '�����';

$strField = '�����';
$strFieldHasBeenDropped = '��� ����� %s';
$strFields = ' ��� ������';
$strFieldsEmpty = ' ����� ����� ����! ';
$strFieldsEnclosedBy = '��� ���� ��';
$strFieldsEscapedBy = '��� ������� ��';
$strFieldsTerminatedBy = '��� ����� ��';
$strFixed = '����';
$strFlushTable = '����� ����� ������ ("FLUSH")';
$strFormat = '����';
$strFormEmpty = '���� ���� ������ �������� !';
$strFullText = '���� �����';
$strFunction = '����';

$strGenTime = '���� ��';
$strGo = '&nbsp;�������&nbsp;';
$strGrants = 'Grants';
$strGzip = '"gzipped"';

$strHasBeenAltered = '��� �����.';
$strHasBeenCreated = '��� ����.';
$strHome = '������ ��������';
$strHomepageOfficial = '������ �������� ������� �� phpMyAdmin';
$strHomepageSourceforge = 'Sourceforge phpMyAdmin ���� �������';
$strHost = '������';
$strHostEmpty = '��� �������� ����!';

$strIdxFulltext = '���� ������';
$strIfYouWish = '��� ��� ���� �� �� ���� ��� ����� ������ ���, ��� �������� ���� ���� ����� �����.';
$strIgnore = '�����';
$strIndex = '�����';
$strIndexes = '�����';
$strIndexHasBeenDropped = '����� ������ %s';
$strIndexName = '��� ������&nbsp;:';
$strIndexType = '��� ������&nbsp;:';
$strInsert = '�����';
$strInsertAsNewRow = '����� ������ ����';
$strInsertedRows = '���� �����:';
$strInsertNewRow = '����� ����� ����';
$strInsertTextfiles = '����� ��� ��� �� ������';
$strInstructions = '�������';
$strInUse = '��� ���������';
$strInvalidName = '"%s" ���� ������, ������� ��������� ���� ����� ������/����/���.';

$strKeepPass = '������ ���� ����';
$strKeyname = '��� �������';
$strKill = '�����';

$strLength = '�����';
$strLengthSet = '�����/������*';
$strLimitNumRows = '��� ������� ��� ����';
$strLineFeed = '���� �����: \\n';
$strLines = '����';
$strLinesTerminatedBy = '���� ������ ��';
$strLocationTextfile = '���� ��� ���';
$strLogin = '����';
$strLogout = '����� ����';
$strLogPassword = '���� ����:';
$strLogUsername = '��� ���������:';

$strModifications = '��� ���������';
$strModify = '�����';
$strModifyIndexTopic = '����� �������';
$strMoveTable = '��� ���� ��� (����� ������<b>.</b>����):';
$strMoveTableOK = '%s ���� �� ���� ��� %s.';
$strMySQLReloaded = '�� ����� ����� MySQL �����.';
$strMySQLSaid = 'MySQL ���: ';
$strMySQLServerProcess = 'MySQL %pma_s1%  ��� ������ %pma_s2% -  �������� : %pma_s3%';
$strMySQLShowProcess = '��� ��������';
$strMySQLShowStatus = '��� ���� ������ MySQL';
$strMySQLShowVars ='��� ������� ������ MySQL';

$strName = '�����';
$strNext = '������';
$strNo = '��';
$strNoDatabases = '������ ����� ������';
$strNoDropDatabases = '���� "��� ����� ������"����� ';
$strNoFrames = 'phpMyAdmin ���� ������ �� ������ <b>��������</b>.';
$strNoIndex = '���� ��� ����!';
$strNoIndexPartsDefined = '����� ������� ��� �����!';
$strNoModification = '�� �������';
$strNone = '����';
$strNoPassword = '�� ���� ��';
$strNoPrivileges = '������ ��� �����';
$strNoQuery = '���� ������� SQL!';
$strNoRights = '��� ���� ������ ������� ��� ���� ��� ����!';
$strNoTablesFound = '������ ����� ������ �� ����� �������� ���!.';
$strNotNumber = '��� ��� ���!';
$strNotValidNumber = ' ��� ��� ��� �� ����!';
$strNoUsersFound = '��������(���) �� ��� �������.';
$strNull = '����';

$strOftenQuotation = '������ ������ ��������. ������� ���� ��� ������  char � varchar ���� �� " ".';
$strOptimizeTable = '��� ������';
$strOptionalControls = '�������. ������ �� ����� ����� �� ����� ������ �� ����� ������.';
$strOptionally = '�������';
$strOr = '��';
$strOverhead = '������';

$strPartialText = '���� �����';
$strPassword = '���� ����';
$strPasswordEmpty = '���� ���� ����� !';
$strPasswordNotSame = '����� ���� ��� ��������� !';
$strPHPVersion = ' PHP ������';
$strPmaDocumentation = '������� ������� �� phpMyAdmin (�����������)';
$strPmaUriError = '������� <span dir="ltr"><tt>$cfg[\'PmaAbsoluteUri\']</tt></span> ��� ������ �� ��� ������� !';
$strPos1 = '�����';
$strPrevious = '����';
$strPrimary = '�����';
$strPrimaryKey = '����� �����';
$strPrimaryKeyHasBeenDropped = '��� �� ��� ������� �������';
$strPrimaryKeyName = '��� ������� ������� ��� �� ���� �����... PRIMARY!';
$strPrimaryKeyWarning = '("�������" <b>���</b> ��� �� ���� ����� <b>������ ���</b> ������� �������!)';
$strPrintView = '��� ���� �������';
$strPrivileges = '����������';
$strProperties = '�����';

$strQBE = '������� ������ ����';
$strQBEDel = 'Del';
$strQBEIns = 'Ins';
$strQueryOnDb = '�� ����� �������� SQL-������� <b>%s</b>:';

$strRecords = '���������';
$strReferentialIntegrity = '����� referential integrity:';
$strReloadFailed = ' ����� ����� �����MySQL.';
$strReloadMySQL = '����� ����� MySQL';
$strRememberReload = '����� ������ ����� ������.';
$strRenameTable = '����� ��� ���� ���';
$strRenameTableOK = '�� ����� ����� ��� %s  ����%s';
$strRepairTable = '����� ������';
$strReplace = '�������';
$strReplaceTable = '������� ������ ������ ������';
$strReset = '�����';
$strReType = '��� �����';
$strRevoke = '�����';
$strRevokeGrant = '����� Grant';
$strRevokeGrantMessage = '��� ����� ������ Grant �� %s';
$strRevokeMessage = '��� ����� ���������� �� %s';
$strRevokePriv = '����� ��������';
$strRowLength = '��� ����';
$strRows = '����';
$strRowsFrom = '���� ���� ��';
$strRowSize = ' ���� ���� ';
$strRowsModeHorizontal = '����';
$strRowsModeOptions = ' %s � ����� ������ ��� %s ���';
$strRowsModeVertical = '�����';
$strRowsStatistic = '��������';
$strRunning = ' ��� ������ %s';
$strRunQuery = '����� ���������';
$strRunSQLQuery = '����� �������/��������� SQL ��� ����� ������ %s';

$strSave = '�����';
$strSelect = '������';
$strSelectADb = '���� ����� ������ �� �������';
$strSelectAll = '����� ����';
$strSelectFields = '������ ���� (��� ����� ����):';
$strSelectNumRows = '�� ���������';
$strSend = '��� ����';
$strServerChoice = '������ ������';
$strServerVersion = '������ ������';
$strSetEnumVal = '��� ��� ��� ����� �� "enum" �� "set", ������ ����� ����� �������� ��� �������: \'a\',\'b\',\'c\'...<br />��� ��� ����� ��� ��� ����� ������ ������� ������ ("\") �� ����� �������� ������� ("\'") ���� ��� ��� �����, ������ ����� ����� ������ (����� \'\\\\xyz\' �� \'a\\\'b\').';
$strShow = '���';
$strShowAll = '���� ����';
$strShowCols = '���� �������';
$strShowingRecords = '������ ������� ';
$strShowPHPInfo = '��� ��������� �������� �  PHP';
$strShowTables = '���� ������';
$strShowThisQuery = ' ��� ��� ��������� ��� ��� ���� ';
$strSingly = '(����)';
$strSize = '�����';
$strSort = '�����';
$strSpaceUsage = '������� ��������';
$strSQLQuery = '�������-SQL';
$strStatement = '�����';
$strStrucCSV = '������ CSV';
$strStrucData = '������ ���������';
$strStrucDrop = ' ����� \'��� ���� ��� ��� ������\' �� �������';
$strStrucExcelCSV = '������ CSV �������  Ms Excel';
$strStrucOnly = '������ ���';
$strSubmit = '�����';
$strSuccess = '����� �� �� ������ ����� SQL-�������';
$strSum = '�������';

$strTable = '������ ';
$strTableComments = '������� ��� ������';
$strTableEmpty = '��� ������ ����!';
$strTableHasBeenDropped = '���� %s �����';
$strTableHasBeenEmptied = '���� %s ������ ���������';
$strTableHasBeenFlushed = '��� �� ����� ����� ������ %s  �����';
$strTableMaintenance = '����� ������';
$strTables = '%s  ���� (�����)';
$strTableStructure = '���� ������';
$strTableType = '��� ������';
$strTextAreaLength = ' ���� ����,<br /> ��� ������� �� ��� ����� ��� ���� ������� ';
$strTheContent = '��� �� ����� ������� ����.';
$strTheContents = '��� �� ������� ������� ������ ������ ������ �������� ������ �� ������� ������� ���� �������� �����.';
$strTheTerminator = '���� ������.';
$strTotal = '�������';
$strType = '�����';

$strUncheckAll = '����� ����� ����';
$strUnique = '����';
$strUnselectAll = '����� ����� ����';
$strUpdatePrivMessage = '��� ���� ����� ���������� �� %s.';
$strUpdateProfile = '����� ����� �������:';
$strUpdateProfileMessage = '��� �� ����� ����� �������.';
$strUpdateQuery = '����� �������';
$strUsage = '�������';
$strUseBackquotes = '����� ����� ������� � ������ � "`" ';
$strUser = '��������';
$strUserEmpty = '��� �������� ����!';
$strUserName = '��� ��������';
$strUsers = '����������';
$strUseTables = '������ ������';

$strValue = '������';
$strViewDump = '��� ���� ������ ';
$strViewDumpDB = '��� ���� ����� ��������';

$strWelcome = '����� �� �� %s';
$strWithChecked = ': ��� ������';
$strWrongUser = '��� ��� ��������/���� ����. ������ �����.';

$strYes = '���';

$strZip = '"zipped" "�����"';

$strAllTableSameWidth = '���� �� ������� ���� ����ֿ';

$strBeginCut = '��� �����';  
$strBeginRaw = '��� ������ ������';  

$strCantLoadRecodeIconv = '�� ���� ����� iconv �� ����� ����� �������� ������� ������ ����� �����ݡ ������ ����� PHP ����� �������� ��� ���������� �� ��� ��� ������� �� phpMyAdmin.';  
$strCantUseRecodeIconv = '�� ���� ������� iconv ��� libiconv ��� ����� recode_string �� ��� ���� �������� ��� �����. ����� �� ������� PHP.';  
$strChangeDisplay = '���� ����� �������';  
$strCharsetOfFile = '����� ���� �����:'; 
$strChoosePage = '����� ���� ���� ��������';  
$strColComFeat = '����� ������� ������';  
$strComments = '�������';  
$strConfigFileError = '�� ����� phpMyAdmin �� ���� ��� ��������!<br />�� ���� ��� ���� �� PHP ��� ��� �� ������� ��� �� ��� �� ������ �� ��� �����.<br />����� ���� ����� ���� ����� �������� ������ ����� ����� ����� ����� �������. �� ���� ������� �� ���� ����� ������� �� ����� ������� �������� ����� �� ���� ��.<br />�� ���� ��� ���� ����ɡ ���� ��� ��� �� ����.';
$strConfigureTableCoord = '���� ����� ������ ������ %s';  
$strCreatePage = '���� ���� �����';  
$strCreatePdfFeat = '����� ����� PDF';  

$strDisabled = '�����';  
$strDisplayFeat = '����� �������';  
$strDisplayPDF = '����� ���� ��� PDF';  
$strDumpXRows = '���� %s ��� ���� �� ����� %s.'; 

$strEditPDFPages = '���� ����� PDF';  
$strEnabled = '�����';  
$strEndCut = '������ �����';  
$strEndRaw = '������ �������� �������';  
$strExplain = '���� SQL';  
$strExport = '�����';  
$strExportToXML = '����� ������ XML'; 

$strGenBy = '���� ������'; 
$strGeneralRelationFeat = '������� ������� ������';  

$strHaveToShow = '���� ������ ���� ���� ��� ����� �����';  

$strLinkNotFound = '�� ���� ����� ������';  
$strLinksTo = '����� ��';  

$strMissingBracket = '���� ��� ����';  
$strMySQLCharset = '����� ���� MySQL';  

$strNoDescription = '���� ���';  
$strNoExplain = '����� ��� SQL';  
$strNoPhp = '���� ����� PHP';  
$strNotOK = '��� ������';  
$strNotSet = '������ <b>%s</b> ��� ����� �� ���� �� %s';  
$strNoValidateSQL = '����� ������� �� SQL';  
$strNumSearchResultsInTable = '%s ������ �� ������ <i>%s</i>';
$strNumSearchResultsTotal = '<b>�������:</b> <i>%s</i>������';

$strOK = '�����';  
$strOperations = '�������';  
$strOptions = '������';  

$strPageNumber = '���� ���:';  
$strPdfDbSchema = '���� ����� �������� "%s" - ������ %s';  
$strPdfInvalidPageNum = '��� ���� PDF ��� �����!';  
$strPdfInvalidTblName = '������ "%s" ��� �����!';  
$strPdfNoTables = '�� ���� �����';  
$strPhp = '���� ����� PHP';  

$strRelationNotWorking = '��� ����� ������� �������� ����� �������� ���������. ������ ����� ���� %s���%s.';  
$strRelationView = '��� �������';  

$strScaleFactorSmall = '���� ����� ������� ����� ��� ������� ������ �� ���� �����.';  
$strSearch = '����';
$strSearchFormTitle = '���� �� ����� ��������';
$strSearchInTables = '���� ������)�������(:';
$strSearchNeedle = '������� �� ����� ������� ����� ���� (wildcard: "%"):';
$strSearchOption1 = '��� ����� ��� �������';
$strSearchOption2 = '�� �������';
$strSearchOption3 = '������ ������';
$strSearchOption4 = '����� ������';
$strSearchResultsFor = '���� �� ������� �� "<i>%s</i>" %s:';
$strSearchType = '����:';
$strSelectTables = '���� �������';  
$strShowColor = '���� �����';  
$strShowGrid = '���� ����� ������';  
$strShowTableDimension = '����� ����� �������';  
$strSplitWordsWithSpace = '������� ������ ���� ����� (" ").';
$strSQL = 'SQL'; 
$strSQLParserBugMessage = '���� ������ ��� ���� ��� ��� �� ����� SQL. ����� ����� �������� ����ɡ ������ �� �� ������ ������� ����� ��������. ��� ����� ������� ������ �� ���� ��� ����� ����� ��� ����� ��� ������ ��� ����� ���� ����� �������. ����� ����� ����� �������� ������ ��� ����� MySQL. �� ������ ����� ��� ���� MySQL ����� �� ���� ���� ����ɡ ��� ����� �������. �� ��� ���� ����� �� �� ���� ������� �� ��� ��� ������� ��� ������ѡ ����� ���� ��� �������� �������� ���� ���� ������ɡ ��� ������ ����� ��� �� ��� �������� �� ����� ����� �����:';  
$strSQLParserUserError = '���� �� ���� ��� �� ������� SQL. ��� ������ ����� ����� �� ���� MySQL ����� �� ����� ������ɡ �� ��� ���� ����ɡ.';  
$strSQLResult = '���� ������� SQL'; 
$strSQPBugInvalidIdentifer = '����� ��� ����';  
$strSQPBugUnclosedQuote = '����� ����� ��� �����';  
$strSQPBugUnknownPunctuation = '�� ����� ��� �����';  
$strStructPropose = '����� ���� ������';  
$strStructure = '����';  

$strValidateSQL = '������ �� ������� SQL';  

$strInsecureMySQL = 'Your configuration file contains settings (root with no password) that correspond to the default MySQL privileged account. Your MySQL server is running with this default, is open to intrusion, and you really should fix this security hole.';  
$strWebServerUploadDirectory = '���� ����� ������� ��� ���� ������';  
$strWebServerUploadDirectoryError = 'The directory you set for upload work cannot be reached';  
$strValidatorError = 'The SQL validator could not be initialized. Please check if you have installed the necessary php extensions as described in the %sdocumentation%s.'; 
$strServer = '���� %s';  
$strPutColNames = '�� ����� ������ �� ����� �����';  
$strImportDocSQL = '������� ����� docSQL';  
$strDataDict = '����� ��������';  
$strPrint = '����';  
$strPHP40203 = '��� ������ ������� 4.2.3 �� PHP ����� ����� ��� ���� ���� �� ������� �� ������ ������ ������ (mbstring). ���� �� ����� ��� PHP ��� 19404. �� ���� �������� ��� ������ �� PHP �� phpMyAdmin.';  
$strCompression = '�����'; 
$strNumTables = '�����'; 
$strTotalUC = '����� ����'; 
$strRelationalSchema = '���� ����������';  
$strTableOfContents = '���� ���������';  
$strCannotLogin = '�� ���� ������ ��� ���� MySQL';  
$strShowDatadictAs = '����� ����� ��������';  
$strLandscape = '��� ������';  
$strPortrait = '��� ������';  

$timespanfmt = '%s ��� %s ���ɡ %s ����� �%s �����'; 

$strAbortedClients = '����'; 
$strConnections = '�������'; 
$strFailedAttempts = '������� �����'; 
$strGlobalValue = '���� �����'; 
$strMoreStatusVars = '�������� ���� ������'; 
$strPerHour = '��� ����'; 
$strQueryStatistics = '<b>�������� ���������</b>: %s ������� ���� ��� ������ ��� ������.';
$strQueryType = '��� ���������'; 
$strReceived = '�������'; 
$strSent = '������'; 
$strServerStatus = '������ �������'; 
$strServerStatusUptime = '��� ��� ��� ���� MySQL ��� %s. ��� ����� �� %s.'; 
$strServerTabVariables = '��������'; 
$strServerTabProcesslist = '��������'; 
$strServerTrafficNotes = '<b>���� ������</b>: ���� ��� ������� �������� ���� ������ ������ ���� ������ ��� ������.';
$strServerVars = '�������� �������� ������'; 
$strSessionValue = '���� ������'; 
$strTraffic = '������ ���'; 
$strVar = '������'; 

$strCommand = '����'; 
$strCouldNotKill = '�� ����� phpMyAdmin ����� �������� %s. ���� ���� ����� ������.'; 
$strId = '���'; 
$strProcesslist = '��� ���������'; 
$strStatus = '����'; 
$strTime = '���'; 
$strThreadSuccessfullyKilled = '�� ����� �������� %s �����.'; 

$strBzError = '�� ����� phpMyAdmin ��� ��� �������� ���� ��� �� ������ Bz2 �� ����� PHP. ������ �� ����� ���� ����� <code>$cfg[\'BZipDump\']</code> �� ��� ������� phpMyAdmin ��� <code>FALSE</code>. �� ��� ���� ������� ����� ��� Bz2� ���� �������� ��� ����� ���� �� PHP. ����� �� �������� ���� �� ����� ��� PHP %s.'; 
$strLaTeX = '��������';  

$strAdministration = '�����'; 
$strFlushPrivilegesNote = '������: ���� phpMyAdmin ������� ���������� �� ����� ��������� �� ���� MySQL �������. ������� ��� ������� �� ����� �� ��������� ���� �������� ������ ��� �� ��� ������� ����� �������. �� ��� �����ɡ ���� %s ������ ����� ��������� %s ��� �� ����.'; 
$strGlobalPrivileges = '�������� �����'; 
$strGrantOption = '������'; 
$strPrivDescAllPrivileges = '������ �� ���������� ��� GRANT.'; 
$strPrivDescAlter = '���� ������ ���� ������� �������� ������.'; 
$strPrivDescCreateDb = '���� ������ ����� ������ ������ �����.'; 
$strPrivDescCreateTbl = '���� ������ ����� �����.'; 
$strPrivDescCreateTmpTable = '���� ������ ����� ������.'; 
$strPrivDescDelete = '���� ���� ��������.'; 
$strPrivDescDropDb = '���� ���� ����� ��������.'; 
$strPrivDescDropTbl = '���� ���� �������.'; 
$strPrivDescExecute = '���� ������ ��������� �������� )stored procedures(� ��� �� �� ����� �� ��� ������ �� ���� MySQL.'; 
$strPrivDescFile = '���� �������� ������ �������� �� ���� ��������.'; 
$strPrivDescGrant = '���� ������ ���������� ���������� ��� ����� ����� ����� ���������.'; 
$strPrivDescIndex = '���� ������ ���� �������.'; 
$strPrivDescInsert = '���� ������ �������� ��������.'; 
$strPrivDescLockTables = '���� ���� ������� �������� ��������.'; 
$strPrivDescMaxConnections = '���� �� ��� ��������� ������� ���� ���� �������� ����� ��� ����.';
$strPrivDescMaxQuestions = '���� ��� ����������� ���� ������ �������� ������� ��� ������ ��� ����.';
$strPrivDescMaxUpdates = '���� ��� ������� ���� ������ �������� ��� ���ɡ ����� ���� �� ���� �� ����� ������.';
$strPrivDescProcess3 = '���� ������ ������� ���������� �������.'; 
$strPrivDescProcess4 = '���� ���� ��������� ������� �� ��� ��������.'; 
$strPrivDescReferences = '��� �� �� ����� �� ���� MySQL ��������.'; 
$strPrivDescReplClient = 'Gives the right to the user to ask where the slaves / masters are.'; 
$strPrivDescReplSlave = '����� ������ ��������.'; 
$strPrivDescReload = 'Allows reloading server settings and flushing the server\'s caches.'; 
$strPrivDescSelect = '���� ������ ��������.'; 
$strPrivDescShowDb = '���� ������� ������ ����� ���� ����� ��������.'; 
$strPrivDescShutdown = '���� ������ ��� ������.'; 
$strPrivDescSuper = '���� �������� ��� �� ��� ��� ��� ��������� ������.� ����� ������ �������� ���� ��������� ������� other users.'; 
$strPrivDescUpdate = '���� ������ ��������.'; 
$strPrivDescUsage = '�� �������.'; 
$strPrivilegesReloaded = '�� ����� ����� ��������� �����.'; 
$strResourceLimits = '���� �������'; 
$strUserOverview = '������� ��������'; 
$strZeroRemovesTheLimit = '������: ����� ��� �������� ����� 0 )���( ���� �����.'; 

$strPasswordChanged = '�� ����� ���� ������ �� %s �����.'; 

$strDeleteAndFlush = '���� ���������� ��� ������ ����� ��������� ��� ���.'; 
$strDeleteAndFlushDescr = '��� �� ���� ����ɡ ��� ����� ����� ��������� �� ������ ��� �����.'; 
$strDeleting = '���� ���� %s'; 
$strJustDelete = '��� �� ���� ���������� �� ���� ���������.'; 
$strJustDeleteDescr = '��� ���� ���������� &quot;���������&quot; ������ ��� ������ ������ ������� ��� ��� ����� ����� ���������.'; 
$strReloadingThePrivileges = '��� ����� ����� ���������.'; 
$strRemoveSelectedUsers = '���� ���������� ��������'; 
$strRevokeAndDelete = '������ �� ��������� ������� �� ���������� �� ������ ��� ���.'; 
$strRevokeAndDeleteDescr = '��� ���� �������� USAGE ��� ���������� ��� ��� ����� ����� ���������.'; 
$strUsersDeleted = '�� ��� ���������� �������� �����.'; 

$strAddPrivilegesOnDb = '����� ��������� ��� ����� �������� �������'; 
$strAddPrivilegesOnTbl = '����� ��������� ��� ������ ������'; 
$strColumnPrivileges = '������� ���� ������'; 
$strDbPrivileges = '������� ���� ������ ��������'; 
$strLocalhost = '����';
$strLoginInformation = '������ ������'; 
$strTblPrivileges = '������� ���� �������'; 
$strThisHost = '��� ������'; 
$strUserNotFound = '�������� ������ ��� ����� �� ���� ���������.'; 
$strUserAlreadyExists = '��� �������� %s ����� ������!'; 
$strUseTextField = '������ ��� ���'; 

$strNoUsersSelected = '�� ��� ����� ������.'; 
$strDropUsersDb = '���� ����� �������� ���� ��� ��� ����� ����������.'; 
$strAddedColumnComment = '�� ����� ������� ������';  
$strWritingCommentNotPossible = '����� ������� ��� ����';  
$strAddedColumnRelation = '�� ����� ������� ������';  
$strWritingRelationNotPossible = '����� ������� ��� �����';  
$strImportFinished = '����� ���������';  
$strFileCouldNotBeRead = '�� ���� ����� �����';  
$strIgnoringFile = '����� ����� %s';  
$strThisNotDirectory = '�� ��� ��� ������';  
$strAbsolutePathToDocSqlDir = '������ ����� ������ ������ ��� ���� ������ ��� ���� docSQL';  
$strImportFiles = '������ �������';  
$strDBGModule = '������';  
$strDBGLine = '���';  
$strDBGHits = '���������';  
$strDBGTimePerHitMs = '���/������� ��';  
$strDBGTotalTimeMs = '����� ����� ��';  
$strDBGMinTimeMs = '��� ��ʡ ��';  
$strDBGMaxTimeMs = '���� ��ʡ ��';  
$strDBGContextID = '��� ������';  
$strDBGContext = '������';  
$strCantLoad = '�� ���� ����� �������� %s�<br />���� ���� �� ������� PHP.';  
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
