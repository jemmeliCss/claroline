<?php
/* $Id$ */

/* Translated by Kyriakos Xagoraris <theremon at users.sourceforge.net> */

$charset = 'iso-8859-7';
$text_dir = 'ltr';
$left_font_family = 'verdana, arial, helvetica, geneva, sans-serif';
$right_font_family = 'tahoma, verdana, helvetica, geneva, sans-serif';
$number_thousands_separator = '.';
$number_decimal_separator = ',';
// shortcuts for Byte, Kilo, Mega, Giga, Tera, Peta, Exa
$byteUnits = array('Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB');

$day_of_week = array('���', '���', '���', '���', '���', '���', '���');
$month = array('���', '���', '���', '���', '���', '����', '����', '���', '���', '���', '���', '���');
// See http://www.php.net/manual/en/function.strftime.php to define the
// variable below
$datefmt = '%d %B %Y, ���� %I:%M %p';

// To Arrange

$strAccessDenied = '\'������ ���������';
$strAction = '��������';
$strAddDeleteColumn = '��������/�������� ������ ������';
$strAddDeleteRow = '��������/�������� ������� ���������';
$strAddNewField = '�������� ���� ������';
$strAddPriv = '�������� ���� ���������';
$strAddPrivMessage = '���������� ��� ��������.';
$strAddSearchConditions = '�������� ���� ���� (���� ��� "where" ��������):';
$strAddToIndex = '�������� ��� ��������� &nbsp;%s&nbsp;�������(��)';
$strAddUser = '�������� ���� ������';
$strAddUserMessage = '���������� ��� ��� ������.';
$strAffectedRows = '������������� ��������:';
$strAfter = '���� �� %s';
$strAfterInsertBack = '���������';
$strAfterInsertNewInsert = '�������� ���� ��������';
$strAll = '���';
$strAllTableSameWidth = '�������� ���� ��� ������� �� �� ���� ������;';
$strAlterOrderBy = '������ ����������� ������ ����';
$strAnalyzeTable = '������� ������';
$strAnd = '���';
$strAnIndex = '��� ��������� ���������� ��� %s';
$strAny = '�����������';
$strAnyColumn = '����������� �����';
$strAnyDatabase = '����������� ����';
$strAnyHost = '����������� �������';
$strAnyTable = '������������ �������';
$strAnyUser = '������������ �������';
$strAPrimaryKey = '��� �������� ������ ���������� ��� %s';
$strAscending = '�������';
$strAtBeginningOfTable = '���� ���� ��� ������';
$strAtEndOfTable = '��� ����� ��� ������';
$strAttr = '��������������';

$strBack = '���������';
$strBinary = '�������';
$strBinaryDoNotEdit = '������� - ����� ���������� ������������';
$strBookmarkDeleted = '� ������� ��������.';
$strBookmarkLabel = '�������';
$strBookmarkQuery = '������������ ������ SQL';
$strBookmarkThis = '���������� ����� ��� ������ SQL';
$strBookmarkView = '���� ��������';
$strBrowse = '���������';
$strBzip = '�������� �bzip�';

$strCantLoadMySQL = '��� ������ �� �������� � �������� MySQL,<br />�������� ������� ��� ��������� ��� PHP.';
$strCantRenameIdxToPrimary = '� ����������� ��� ���������� �� PRIMARY �� ����� ������!';
$strCardinality = '������������';
$strCarriage = '���������� ����������: \\r';
$strChange = '������';
$strChangePassword = '������ ������� ���������';
$strCheckAll = '������� ����';
$strCheckDbPriv = '������� ��������� �����';
$strCheckTable = '������� ������';
$strColComFeat = '�������� ������� ������';
$strColumn = '�����';
$strColumnNames = '������� ������';
$strCompleteInserts = '������������� ������� �Insert�';
$strConfirm = '���������� ������ �� �� ����������;';
$strCookiesRequired = '��� ���� �� ������ ������ �� ����� �������������� cookies.';
$strCopyTable = '��������� ������ �� (����<b>.</b>�������):';
$strCopyTableOK = '� ������� %s ����������� ��� %s.';
$strCreate = '����������';
$strCreateIndex = '���������� ���������� �� &nbsp;%s&nbsp;�����';
$strCreateIndexTopic = '���������� ���� ����������';
$strCreateNewDatabase = '���������� ���� �����';
$strCreateNewTable = '���������� ���� ������ ��� ���� %s';
$strCreatePdfFeat = '���������� ������� PDF';
$strCriteria = '��������';

$strData = '��������';
$strDatabase = '���� ';
$strDatabaseHasBeenDropped = '� ���� ��������� %s ��������.';
$strDatabases = '������';
$strDatabasesStats = '���������� �����';
$strDatabaseWildcard = '���� ��������� (������������ wildcards):';
$strDataOnly = '���� �� ��������';
$strDefault = '��������������';
$strDelete = '��������';
$strDeleted = '� ������� ���� ���������';
$strDeletedRows = '������������ ��������:';
$strDeleteFailed = '� �������� �������';
$strDeleteUserMessage = '���������� ��� ������ %s.';
$strDescending = '��������';
$strDisabled = '����������������';
$strDisplay = '��������';
$strDisplayFeat = '����������� ���������';
$strDisplayOrder = '����� ���������:';
$strDoAQuery = '�������� ��� ���������� ���� ���������� (���������� ��������� "%")';
$strDocu = '����������';
$strDoYouReally = '������ �� ���������� ��� ������';
$strDrop = '��������';
$strDropDB = '�������� ����� %s';
$strDropTable = '�������� ������';
$strDumpingData = '\'�������� ��������� ��� ������';
$strDynamic = '��������';

$strEdit = '�����������';
$strEditPrivileges = '����������� ���������';
$strEffective = '���������������';
$strEmpty = '\'��������';
$strEmptyResultSet = '� MySQL ��������� ��� ����� ������ ������������� (�.�. ������ �������).';
$strEnabled = '��������������';
$strEnd = '�����';
$strEnglishPrivileges = ' ��������: �� ������� ��������� ��� MySQL ����������� ��� ������� ';
$strError = '�����';
$strExtendedInserts = '����������� ������� �Insert�';
$strExtra = '��������';

$strField = '�����';
$strFieldHasBeenDropped = '�� ����� %s ��������';
$strFields = '�����';
$strFieldsEmpty = ' � ���������� ��� ������ ����� ����! ';
$strFieldsEnclosedBy = '����� ��� ������������� ��';
$strFieldsEscapedBy = '�� ����� ������������� �� ��������� �������� ';
$strFieldsTerminatedBy = '����� ��� ���������� ��';
$strFixed = '��������������� ������';
$strFlushTable = '���������� ("FLUSH") ������';
$strFormat = '�����������';
$strFormEmpty = '�������� ���� ��� ����� !';
$strFullText = '����� �������';
$strFunction = '�������';

$strGeneralRelationFeat = '������� ����������� ����������';
$strGenTime = '������ �����������';
$strGo = '��������';
$strGrants = '������������';
$strGzip = '�������� �gzip�';

$strHasBeenAltered = '���� ��������.';
$strHasBeenCreated = '���� ������������.';
$strHome = '�������� ������';
$strHomepageOfficial = '������� ������ ��� phpMyAdmin';
$strHomepageSourceforge = '������ ��� Sourceforge ��� ��� �������� ��� phpMyAdmin';
$strHost = '�������';
$strHostEmpty = '�� ����� ��� ���������� ����� ����!';

$strIdxFulltext = '������ �������';
$strIfYouWish = '�� ������������ �� ��������� ���� ������� ��� ��� ������ ��� ������, ��������� ��� ����� ������ ������������ �� �����.';
$strIgnore = '��������';
$strIndex = '���������';
$strIndexes = '���������';
$strIndexHasBeenDropped = '�� ��������� %s ��������';
$strIndexName = '����� ����������&nbsp;:';
$strIndexType = '����� ����������&nbsp;:';
$strInsert = '��������';
$strInsertAsNewRow = '�������� �� ��� ��������';
$strInsertedRows = '����������� ��������:';
$strInsertNewRow = '�������� ���� ��������';
$strInsertTextfiles = '�������� ������� �������� ���� ������';
$strInstructions = '�������';
$strInUse = '�� �����';
$strInvalidName = '� �%s� ����� ���������� ����, ��� �������� �� ��� ��������������� �� ����� ��� ����, ������ � �����.';

$strKeepPass = '��������� ������� ���������';
$strKeyname = '����� ��������';
$strKill = '�����������';

$strLength = '�����';
$strLengthSet = '�����/�����*';
$strLimitNumRows = '�������� ��� ������';
$strLineFeed = '���������� ��������� �������: \\n';
$strLines = '�������';
$strLinesTerminatedBy = '������� ��� ���������� ��';
$strLocationTextfile = '��������� ��� ������� ��������';
$strLogin = '�������';
$strLogout = '����������';
$strLogPassword = '������� ���������:';
$strLogUsername = '����� ������:';

$strModifications = '�� ������� �������������';
$strModify = '�����������';
$strModifyIndexTopic = '������ ���� ����������';
$strMoveTable = '�������� ������ �� (����<b>.</b>�������):';
$strMoveTableOK = '� ������� %s ����������� ��� %s.';
$strMySQLReloaded = '� MySQL ��������������.';
$strMySQLSaid = '� MySQL ��������� �� ������: ';
$strMySQLServerProcess = '� MySQL %pma_s1% ���������� ���� %pma_s2% �� %pma_s3%';
$strMySQLShowProcess = '�������� ����������';
$strMySQLShowStatus = '�������� ���������� ��������� ��� MySQL';
$strMySQLShowVars = '�������� ���������� ��� MySQL';

$strName = '�����';
$strNext = '�������';
$strNo = '���';
$strNoDatabases = '��� �������� ������ ���������';
$strNoDropDatabases = '�� ������� �DROP DATABASE� ����� ���������������.';
$strNoFrames = '�� phpMyAdmin ����� ��� ������ �� ���� browser <b>��� ����������� frames</b>.';
$strNoIndex = '��� �������� ���������!';
$strNoIndexPartsDefined = '��� ��������� �� �������� ��� ����������!';
$strNoModification = '����� ������';
$strNone = '������';
$strNoPassword = '����� ������ ���������';
$strNoPrivileges = '����� ��������';
$strNoQuery = '��� ������� ������ SQL!';
$strNoRights = '��� ����� ������ ���������� �� ������� ��� ����!';
$strNoTablesFound = '��� �������� ������� ��� ����.';
$strNotNumber = '���� ��� ����� �������!';
$strNotOK = '�����';
$strNotValidNumber = ' ��� ����� �������� ������� ��������!';
$strNoUsersFound = '��� �������� �������.';
$strNull = '����';

$strOftenQuotation = '����� ����������. �� OPTIONALLY �������� ��� ���� �� ����� char ��� varchar ������������� �� ��� ��������� ���������������� ����.';
$strOK = 'OK';
$strOptimizeTable = '�������������� ������';
$strOptionalControls = '�����������. �������� ��� �� ������� � �������� ��� � ������� ������� ����������.';
$strOptionally = '�����������';
$strOr = '�';
$strOverhead = '����������';

$strPartialText = '��������� �������';
$strPassword = '������� ���������';
$strPasswordEmpty = '� ������� ��������� ����� �����!';
$strPasswordNotSame = '�� ������� ��������� ��� ����� �����!';
$strPdfNoTables = '��� �������� �������';
$strPHPVersion = '������ PHP';
$strPmaDocumentation = '���������� phpMyAdmin';
$strPmaUriError = '� ������ <tt>$cfg[\'PmaAbsoluteUri\']</tt> ������ �� ������� ��� ������ �����������!';
$strPos1 = '����';
$strPrevious = '�����������';
$strPrimary = '��������';
$strPrimaryKey = '�������� ������';
$strPrimaryKeyHasBeenDropped = '�� �������� ������ ��������';
$strPrimaryKeyName = '�� ����� ��� ����������� �������� ������ �� �����... PRIMARY!';
$strPrimaryKeyWarning = '("PRIMARY" <b>������</b> �� ����� �� ����� ��� ����������� �������� ��� <b>���� �����</b> !)';
$strPrintView = '�������� ��� ��������';
$strPrivileges = '��������';
$strProperties = '���������';

$strQBE = '��������� ���� ����������';
$strQBEDel = '��������';
$strQBEIns = '��������';
$strQueryOnDb = '������ SQL ��� ���� <b>%s</b>:';

$strRecords = '��������';
$strReferentialIntegrity = '������� ������������ �������:';
$strRelationNotWorking = '�� ������������ ����������� ��� ������� �� �������������� ������� ����� ���������������. ��� �� ������ �����, ������� %s���%s.';
$strReloadFailed = '� ������������ ��� MySQL �������.';
$strReloadMySQL = '������������ ��� MySQL';
$strRememberReload = '�������� ��� ������������� ��� ����������.';
$strRenameTable = '����������� ������ ��';
$strRenameTableOK = '� ������� %s ������������� �� %s';
$strRepairTable = '����������� ������';
$strReplace = '�������������';
$strReplaceTable = '������������� ��������� ������ �� �� ������';
$strReset = '���������';
$strReType = '�������������';
$strRevoke = '��������';
$strRevokeGrant = '�������� �����������';
$strRevokeGrantMessage = '����������� �� �������� ����������� ��� %s';
$strRevokeMessage = '����������� �� �������� ��� %s';
$strRevokePriv = '�������� ����������';
$strRowLength = '������� �������';
$strRows = '��������';
$strRowsFrom = '�������� ���������� ��� ��� �������';
$strRowSize = ' ������� �������� ';
$strRowsModeHorizontal = '���������';
$strRowsModeOptions = '�� %s ����� �� ��������� ������������ ��� %s �����';
$strRowsModeVertical = '������';
$strRowsStatistic = '���������� ��������';
$strRunning = '��� ���������� ��� %s';
$strRunQuery = '������� ����������';
$strRunSQLQuery = '�������� �������/������� SQL ��� ���� ��������� %s';

$strSave = '����������';
$strSelect = '�������';
$strSelectADb = '�������� �������� ��� ���� ���������';
$strSelectAll = '������� ����';
$strSelectFields = '������� ������ (����������� ���)';
$strSelectNumRows = '���� ������';
$strSend = '��������';
$strServerChoice = '������� ����������';
$strServerVersion = '������ ����������';
$strSetEnumVal = '�� � ����� ��� ������ ����� �enum� � �set�, �������� �������� ��� ����� ��������������� ��� ���� �����������: \'�\',\'�\',\'�\'...<br /> �� ���������� �� �������� ��� ������� ������ ("\") � ���� ���������� ("\'"), �������� �� �� ������� ������ ���� ���� (��� ���������� \'\\\\���\' � \'�\\\'�\').';
$strShow = '��������';
$strShowAll = '�������� ����';
$strShowCols = '�������� ������';
$strShowingRecords = '�������� �������� ';
$strShowPHPInfo = '�������� ����������� ��� PHP';
$strShowTables = '�������� �������';
$strShowThisQuery = ' �������� ��� ���� ����� ��� ������ ';
$strSingly = '(��������)';
$strSize = '�������';
$strSort = '����������';
$strSpaceUsage = '����� �����';
$strSQLQuery = '������ SQL';
$strStatement = '��������';
$strStrucCSV = '�������� CSV';
$strStrucData = '���� ��� ��������';
$strStrucDrop = '�������� �Drop Table�';
$strStrucExcelCSV = '����� CSV ��� �������� Ms Excel';
$strStrucOnly = '���� � ����';
$strSubmit = '��������';
$strSuccess = '� SQL ������ ��� ����������� ��������';
$strSum = '������';

$strTable = '������� ';
$strTableComments = '������ ������';
$strTableEmpty = '�� ����� ��� ������ ����� ����!';
$strTableHasBeenDropped = '� ������� %s ��������';
$strTableHasBeenEmptied = '� ������� %s �������';
$strTableHasBeenFlushed = '� ������� %s ������������� ("FLUSH")';
$strTableMaintenance = '��������� ������';
$strTables = '%s �������/�������';
$strTableStructure = '���� ������ ��� ��� ������';
$strTableType = '����� ������';
$strTextAreaLength = ' �������� ��� ������� ���,<br /> ���� �� ����� ������ �� �� ������ �� ��������� ';
$strTheContent = '�� ����������� ��� ������� ��� ����� ���������.';
$strTheContents = '�� ����������� ��� ������� ������������� �� ����������� ��� ����������� ������ ��� ������� �� ���� �������� � �������� ������.';
$strTheTerminator = '� ���������� ���������� ��� ������.';
$strTotal = '��������';
$strType = '�����';

$strUncheckAll = '��������� ����';
$strUnique = '��������';
$strUnselectAll = '��������� ����';
$strUpdatePrivMessage = '�� �������� ��� ������ %s ������������.';
$strUpdateProfile = '��������� ���������:';
$strUpdateProfileMessage = '�� �������� �����������.';
$strUpdateQuery = '��������� ��� �������';
$strUsage = '�����';
$strUseBackquotes = '����� �������� ����������� ��� ������� ��� ������� ��� ��� ������';
$strUser = '�������';
$strUserEmpty = '�� ����� ��� ������ ����� ����!';
$strUserName = '����� ������';
$strUsers = '�������';
$strUseTables = '����� �������';

$strValue = '����';
$strViewDump = '�������� �������� ��� ������';
$strViewDumpDB = '�������� �������� ��� �����';

$strWelcome = '����������� ��� %s';
$strWithChecked = '�� ���� ������������:';
$strWrongUser = '���������� ����� ������/������� ���������. \'������ ���������.';

$strYes = '���';

$strZip = '�������� �zip�';
// To Translate

$strBeginCut = 'BEGIN CUT';  //to translate
$strBeginRaw = 'BEGIN RAW';  //to translate

$strCantLoadRecodeIconv = '��� ����� ������ � ������� ��� ��������� iconv � recode ��� ���������� ��� ��� ��������� ��� ��� ����������. �������� ��� php �� ��������� ��� ����� ����� ��� ���������� � ��������������� ��� ��������� ���������� ��� phpMyAdmin.';  //to translate
$strCantUseRecodeIconv = '��� ����� ������ � ����� ��� ��������� iconv ���� ��� libiconv ���� ��� �������� recode_string, ��� � �������� ���� ��������. ������ ��� ��������� ��� php.';  //to translate
$strChangeDisplay = '�������� ����� ��� ��������';  //to translate
$strCharsetOfFile = 'Character set of the file:'; //to translate
$strChoosePage = '�������� �������� ������ ��� ������';  //to translate
$strComments = '������';  //to translate
$strConfigFileError = '�� phpMyAdmin ��� ������� �� �������� �� ������ ���������!<br />���� ������ �� ������ ��� � php ���� ������ ����� ��� ������ � ��� � php ��� ������ �� ���� �� ������.<br />�������� ������� �� ������ ��������� ��\' ������� ��������������� �� �������� link ��� �������� �� �������� ������ ��� �� ���������� � php. ���� ������������ ����������� ����� ������� ���������� (") � ����������� (;).<br />��� � php ���������� ��� ����� ������, ��� ����� �����.'; //to translate
$strConfigureTableCoord = '�������� ������ ��� ������������� ��� ��� ������ %s';  //to translate
$strCreatePage = '���������� ���� �������';  //to translate

$strDisplayPDF = '�������� �������� PDF';  //to translate
$strDumpXRows = '�������� %s �������� ���������� ��� ��� ������� %s.'; //to translate

$strEditPDFPages = '������ ������� PDF';  //to translate
$strEndCut = 'END CUT';  //to translate
$strEndRaw = 'END RAW';  //to translate
$strExplain = 'Explain SQL';  //to translate
$strExport = '�������';  //to translate
$strExportToXML = 'Export to XML format'; //to translate

$strGenBy = '������������� ���:'; //to translate

$strHaveToShow = '������ �� ��������� ����������� ��� ����� ��� ��������';  //to translate

$strLinkNotFound = '��� ������� � �������';  //to translate
$strLinksTo = '������� ��';  //to translate

$strMissingBracket = '������ ��� ������';  //to translate
$strMySQLCharset = '��� ���������� ��� MySQL';  //to translate

$strNoDescription = '����� ���������';  //to translate
$strNoExplain = 'Skip Explain SQL';  //to translate
$strNoPhp = '����� ������ PHP';  //to translate
$strNotSet = '� ������� <b>%s</b> ��� ������� � ��� �������� ��� %s';  //to translate
$strNoValidateSQL = 'Skip Validate SQL';  //to translate
$strNumSearchResultsInTable = '%s ������������ ���� ������ <i>%s</i>';//to translate
$strNumSearchResultsTotal = '<b>������:</b> <i>%s</i> ������������';//to translate

$strOperations = '�����������';  //to translate
$strOptions = '��������';  //to translate

$strPageNumber = '������:';  //to translate
$strPdfDbSchema = '����� ��� ����� "%s" - ������ %s';  //to translate
$strPdfInvalidPageNum = '��� �������� ������� ������� PDF!';  //to translate
$strPdfInvalidTblName = '� ������� "%s" ��� �������!';  //to translate
$strPhp = '���������� ������ PHP';  //to translate

$strRelationView = '�������� �������';  //to translate

$strScaleFactorSmall = '� ������� ����� ���� ����� ��� �� ���������� �� ����� �� ��� ������';  //to translate
$strSearch = '���������';//to translate
$strSearchFormTitle = '��������� ��� ����';//to translate
$strSearchInTables = '���� ����� �������:';//to translate
$strSearchNeedle = '���� � ����� ��� ��������� (���������: "%"):';//to translate
$strSearchOption1 = '����������� ���� ��� ���� �����';//to translate
$strSearchOption2 = '����� ���� �����';//to translate
$strSearchOption3 = '��� ������ �����';//to translate
$strSearchOption4 = '�� regular expression';//to translate
$strSearchResultsFor = '������������ ���������� ��� "<i>%s</i>" %s:';//to translate
$strSearchType = '������:';//to translate
$strSelectTables = '������� �������';  //to translate
$strShowColor = '�������� ��������';  //to translate
$strShowGrid = '�������� ���������';  //to translate
$strShowTableDimension = '�������� ���������� �������';  //to translate
$strSplitWordsWithSpace = '�� ������ ���������� ��� ��� ��������� ����������� (" ").';//to translate
$strSQL = 'SQL'; //to translate
$strSQLParserBugMessage = 'There is a chance that you may have found a bug in the SQL parser. Please examine your query closely, and check that the quotes are correct and not mis-matched. Other possible failure causes may be that you are uploading a file with binary outside of a quoted text area. You can also try your query on the MySQL command line interface. The MySQL server error output below, if there is any, may also help you in diagnosing the problem. If you still have problems or if the parser fails where the command line interface succeeds, please reduce your SQL query input to the single query that causes problems, and submit a bug report with the data chunk in the CUT section below:';  //to translate
$strSQLParserUserError = 'There seems to be an error in your SQL query. The MySQL server error output below, if there is any, may also help you in diagnosing the problem';  //to translate
$strSQLResult = '���������� SQL'; //to translate
$strSQPBugInvalidIdentifer = 'Invalid Identifer';  //to translate
$strSQPBugUnclosedQuote = 'Unclosed quote';  //to translate
$strSQPBugUnknownPunctuation = 'Unknown Punctuation String';  //to translate
$strStructPropose = '������������ ���� ������';  //to translate
$strStructure = '����';  //to translate

$strValidateSQL = 'Validate SQL';  //to translate

$strInsecureMySQL = 'Your configuration file contains settings (root with no password) that correspond to the default MySQL privileged account. Your MySQL server is running with this default, is open to intrusion, and you really should fix this security hole.';  //to translate
$strWebServerUploadDirectory = 'web-server upload directory';  //to translate
$strWebServerUploadDirectoryError = 'The directory you set for upload work cannot be reached';  //to translate
$strValidatorError = 'The SQL validator could not be initialized. Please check if you have installed the necessary php extensions as described in the %sdocumentation%s.'; //to translate
$strServer = 'Server %s';  //to translate
$strPutColNames = 'Put fields names at first row';  //to translate
$strImportDocSQL = 'Import docSQL Files';  //to translate
$strDataDict = 'Data Dictionary';  //to translate
$strPrint = 'Print';  //to translate
$strPHP40203 = 'You are using PHP 4.2.3, which has a serious bug with multi-byte strings (mbstring). See PHP bug report 19404. This version of PHP is not recommended for use with phpMyAdmin.';  //to translate
$strCompression = 'Compression'; //to translate
$strNumTables = 'Tables'; //to translate
$strTotalUC = 'Total'; //to translate
$strRelationalSchema = 'Relational schema';  //to translate
$strTableOfContents = 'Table of contents';  //to translate
$strCannotLogin = 'Cannot login to MySQL server';  //to translate
$strShowDatadictAs = 'Data Dictionary Format';  //to translate
$strLandscape = 'Landscape';  //to translate
$strPortrait = 'Portrait';  //to translate

$timespanfmt = '%s days, %s hours, %s minutes and %s seconds'; //to translate

$strAbortedClients = 'Aborted'; //to translate
$strConnections = 'Connections'; //to translate
$strFailedAttempts = 'Failed attempts'; //to translate
$strGlobalValue = 'Global value'; //to translate
$strMoreStatusVars = 'More status variables'; //to translate
$strPerHour = 'per hour'; //to translate
$strQueryStatistics = '<b>Query statistics</b>: Since its startup, %s queries have been sent to the server.';
$strQueryType = 'Query type'; //to translate
$strReceived = 'Received'; //to translate
$strSent = 'Sent'; //to translate
$strServerStatus = 'Runtime Information'; //to translate
$strServerStatusUptime = 'This MySQL server has been running for %s. It started up on %s.'; //to translate
$strServerTabVariables = 'Variables'; //to translate
$strServerTabProcesslist = 'Processes'; //to translate
$strServerTrafficNotes = '<b>Server traffic</b>: These tables show the network traffic statistics of this MySQL server since its startup.';
$strServerVars = 'Server variables and settings'; //to translate
$strSessionValue = 'Session value'; //to translate
$strTraffic = 'Traffic'; //to translate
$strVar = 'Variable'; //to translate

$strCommand = 'Command'; //to translate
$strCouldNotKill = 'phpMyAdmin was unable to kill thread %s. It probably has already been closed.'; //to translate
$strId = 'ID'; //to translate
$strProcesslist = 'Process list'; //to translate
$strStatus = 'Status'; //to translate
$strTime = 'Time'; //to translate
$strThreadSuccessfullyKilled = 'Thread %s was successfully killed.'; //to translate

$strBzError = 'phpMyAdmin was unable to compress the dump because of a broken Bz2 extension in this php version. It is strongly recommended to set the <code>$cfg[\'BZipDump\']</code> directive in your phpMyAdmin configuration file to <code>FALSE</code>. If you want to use the Bz2 compression features, you should upgrade to a later php version. See php bug report %s for details.'; //to translate
$strLaTeX = 'LaTeX';  //to translate

$strAdministration = 'Administration'; //to translate
$strFlushPrivilegesNote = 'Note: phpMyAdmin gets the users\' privileges directly from MySQL\'s privilege tables. The content of this tables may differ from the privileges the server uses if manual changes have made to it. In this case, you should %sreload the privileges%s before you continue.'; //to translate
$strGlobalPrivileges = 'Global privileges'; //to translate
$strGrantOption = 'Grant'; //to translate
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
$strPrivDescMaxConnections = 'Limits the number of new connections the user may open per hour.';
$strPrivDescMaxQuestions = 'Limits the number of queries the user may send to the server per hour.';
$strPrivDescMaxUpdates = 'Limits the number of commands that change any table or database the user may execute per hour.';
$strPrivDescProcess3 = 'Allows killing processes of other users.'; //to translate
$strPrivDescProcess4 = 'Allows viewing the complete queries in the process list.'; //to translate
$strPrivDescReferences = 'Has no effect in this MySQL version.'; //to translate
$strPrivDescReplClient = 'Gives the right to the user to ask where the slaves / masters are.'; //to translate
$strPrivDescReplSlave = 'Needed for the replication slaves.'; //to translate
$strPrivDescReload = 'Allows reloading server settings and flushing the server\'s caches.'; //to translate
$strPrivDescSelect = 'Allows reading data.'; //to translate
$strPrivDescShowDb = 'Gives access to the complete list of databases.'; //to translate
$strPrivDescShutdown = 'Allows shutting down the server.'; //to translate
$strPrivDescSuper = 'Allows connectiong, even if maximum number of connections is reached; Required for most administrative operations like setting global variables or killing threads of other users.'; //to translate
$strPrivDescUpdate = 'Allows changing data.'; //to translate
$strPrivDescUsage = 'No privileges.'; //to translate
$strPrivilegesReloaded = 'The privileges were reloaded successfully.'; //to translate
$strResourceLimits = 'Resource limits'; //to translate
$strUserOverview = 'User overview'; //to translate
$strZeroRemovesTheLimit = 'Note: Setting these options to 0 (zero) removes the limit.'; //to translate

$strPasswordChanged = 'The Password for %s was changed successfully.'; // to translate

$strDeleteAndFlush = 'Delete the users and reload the privileges afterwards.'; //to translate
$strDeleteAndFlushDescr = 'This is the cleanest way, but reloading the privileges may take a while.'; //to translate
$strDeleting = 'Deleting %s'; //to translate
$strJustDelete = 'Just delete the users from the privilege tables.'; //to translate
$strJustDeleteDescr = 'The &quot;deleted&quot; users will still be able to access the server as usual until the privileges are reloaded.'; //to translate
$strReloadingThePrivileges = 'Reloading the privileges'; //to translate
$strRemoveSelectedUsers = 'Remove selected users'; //to translate
$strRevokeAndDelete = 'Revoke all active privileges from the users and delete them afterwards.'; //to translate
$strRevokeAndDeleteDescr = 'The users will still have the USAGE privilege until the privileges are reloaded.'; //to translate
$strUsersDeleted = 'The selected users have been deleted successfully.'; //to translate

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
