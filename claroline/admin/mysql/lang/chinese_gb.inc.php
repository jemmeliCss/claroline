<?php
/* $Id$ */

/**
 * Last translation by: Siu Sun <siusun@best-view.net>
 */

$charset = 'gb2312';
$text_dir = 'ltr';
$left_font_family = 'sans-serif';
$right_font_family = 'sans-serif';
$number_thousands_separator = ',';
$number_decimal_separator = '.';
// shortcuts for Byte, Kilo, Mega, Giga, Tera, Peta, Exa
$byteUnits = array('Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB');

$day_of_week = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');
$month = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
// See http://www.php.net/manual/en/function.strftime.php to define the
// variable below
$datefmt = '%B %d, %Y at %I:%M %p';

$strAPrimaryKey = '�����Ѿ���ӵ� %s';
$strAccessDenied = '���ʱ��ܾ�';
$strAction = 'ִ�в���';
$strAddDeleteColumn = '���/ɾ�� ѡ����';
$strAddDeleteRow = '���/ɾ�� ɸѡ��';
$strAddNewField = '������ֶ�';
$strAddPriv = '�����Ȩ��';
$strAddPrivMessage = '���Ѿ�Ϊ�´�ʹ�����������Ȩ��.';
$strAddSearchConditions = '��Ӽ������� ("where" ��������)��';
$strAddToIndex = '��� &nbsp;%s&nbsp; ��������';
$strAddUser = '����ʹ����';
$strAddUserMessage = '����������һ����ʹ����.';
$strAffectedRows = 'Ӱ������:';
$strAfter = '�� %s ֮��';
$strAfterInsertBack = '����';
$strAfterInsertNewInsert = '���һ�ʼ�¼';
$strAll = 'ȫ��';
$strAllTableSameWidth = '����ͬ�����ʾ�������ݱ�?';
$strAlterOrderBy = '������λ���������¼';
$strAnIndex = '�����Ѿ���ӵ� %s';
$strAnalyzeTable = '�������ݱ�';
$strAnd = '��';
$strAny = '�κ�';
$strAnyColumn = '�κ���λ';
$strAnyDatabase = '�κ����ݿ�';
$strAnyHost = '�κ�����';
$strAnyTable = '�κ����ݱ�';
$strAnyUser = '�κ�ʹ����';
$strAscending = '����';
$strAtBeginningOfTable = '�����ݱ�ͷ';
$strAtEndOfTable = '�����ݱ�β��';
$strAttr = '����';

$strBack = '����';
$strBinary = ' �������� ';
$strBinaryDoNotEdit = ' �������� - �޷��༭ ';
$strBookmarkDeleted = '��ǩ�Ѿ�ɾ��.';
$strBookmarkLabel = '��ǩ����';
$strBookmarkQuery = 'SQL �﷨��ǩ';
$strBookmarkThis = '����� SQL �﷨����ǩ';
$strBookmarkView = '�鿴';
$strBrowse = '���';
$strBzip = '"bzipped"';

$strCantLoadMySQL = '�������� MySQL ����,<br />���� PHP ����̬�趨';
$strCantLoadRecodeIconv = 'δ�ܶ�ȡ iconv �����±����ʽ�������ֱ���ת��, ���趨 php ��������Щģ���ȡ�� phpMyAdmin ʹ�����ֱ���ת������.';
$strCantRenameIdxToPrimary = '�޷�����������Ϊ PRIMARY!';
$strCantUseRecodeIconv = '���ı���ģ���ȡ��,δ��ʹ�� iconv �� libiconv �� recode_string ����. �������� php �趨.';
$strCardinality = '���';
$strCarriage = '�س�: \\r';
$strChange = '�ı�';
$strChangeDisplay = 'ѡ����ʾ֮�ֶ�';
$strChangePassword = '��������';
$strCheckAll = 'ȫѡ';
$strCheckDbPriv = '������ݿ�Ȩ��';
$strCheckTable = '������ݱ�';
$strChoosePage = '��ѡ����Ҫ�༭��ҳ��';
$strColComFeat = '��ʾ��λע��';
$strColumn = '��λ';
$strColumnNames = '��λ��';
$strComments = 'ע��';
$strCompleteInserts = 'ʹ����������ָ��';
$strCompression = 'ѹ��';
$strConfigFileError = 'phpMyAdmin δ�ܶ�ȡ�����趨��! ���������Ϊ php �ҵ��﷨�ϵĴ���� php δ���ҵ���������.<br />�볢��ֱ�Ӱ����·������Ὺ�����鿴 php �Ĵ�����Ϣ. ͨ���Ĵ�������ĳ��©�����Ż�ֱ�.<br />���������������ֿհ�ҳ, ������û���κ�����.';
$strConfigureTableCoord = '���趨���ݱ� %s �ڵ�����';
$strConfirm = '��ȷ��Ҫ������?';
$strCookiesRequired = 'Cookies �����������ܵ���.';
$strCopyTable = '�������ݱ��� (��ʽΪ ���ݿ�����<b>.</b>���ݱ�����):';
$strCopyTableOK = '���ݱ� %s �Ѿ��ɹ�����Ϊ %s��';
$strCreate = '����';
$strCreateIndex = '���� &nbsp;%s&nbsp; ��������';
$strCreateIndexTopic = '���һ������';
$strCreateNewDatabase = '����һ���µ����ݿ�';
$strCreateNewTable = '����һ���µ����ݱ������ݿ� %s';
$strCreatePage = '������һҳ';
$strCreatePdfFeat = '���� PDF';
$strCriteria = '�淶';

$strData = '����';
$strDataDict = '�����ֵ�';
$strDataOnly = 'ֻ������';
$strDatabase = '���ݿ� ';
$strDatabaseHasBeenDropped = '���ݿ� %s �ѱ�ɾ��';
$strDatabaseWildcard = '���ݿ� (����ʹ�������ַ�):';
$strDatabases = '���ݿ�';
$strDatabasesStats = '���ݿ�ͳ��';
$strDefault = 'ȱʡֵ';
$strDelete = 'ɾ��';
$strDeleteFailed = 'ɾ��ʧ��!';
$strDeleteUserMessage = '���Ѿ����û� %s ɾ��.';
$strDeleted = '�ü�¼�Ѿ���ɾ����';
$strDeletedRows = '��ɾ������:';
$strDescending = '�ݼ�';
$strDisabled = 'δ����';
$strDisplay = '��ʾ';
$strDisplayFeat = '������ʾ';
$strDisplayOrder = '��ʾ����';
$strDisplayPDF = '��ʾ PDF ͼ��';
$strDoAQuery = '��ִ�� "��ѯʾ��" (ͨ���: "%")';
$strDoYouReally = '��ȷ��Ҫ ';
$strDocu = '�ĵ�';
$strDrop = '����';
$strDropDB = '�������ݿ� %s';
$strDropTable = 'ɾ�����ݱ�';
$strDumpXRows = '���� %s ��, �� %s �п�ʼ.';
$strDumpingData = '������������ݿ�����';
$strDynamic = '��̬';

$strEdit = '�༭';
$strEditPDFPages = '�༭ PDF ҳ��';
$strEditPrivileges = '�༭Ȩ��';
$strEffective = '��Ч';
$strEmpty = '���';
$strEmptyResultSet = 'MySQL ���صĲ�ѯ���Ϊ�ա� (ԭ�����Ϊ��û���ҵ����������ļ�¼��)';
$strEnabled = '����';
$strEnd = '����';
$strEnglishPrivileges = ' ע��: MySQL Ȩ�����ƻᱻ���ͳ�Ӣ�� ';
$strError = '����';
$strExplain = '˵�� SQL';
$strExport = '���';
$strExportToXML = '����� XML ��ʽ';
$strExtendedInserts = '�������ģʽ';
$strExtra = '����';

$strField = '�ֶ�';
$strFieldHasBeenDropped = '���ݱ� %s �ѱ�ɾ��';
$strFields = '�ֶ�';
$strFieldsEmpty = ' ��λ�����ǿյ�! ';
$strFieldsEnclosedBy = '����λ��ʹ����Ԫ��';
$strFieldsEscapedBy = '��ESCAPE��ʹ����Ԫ��';
$strFieldsTerminatedBy = '����λ�ָ���ʹ����Ԫ��';
$strFixed = '�̶�';
$strFlushTable = 'ǿ�ȸ������ϱ� ("FLUSH")';
$strFormEmpty = '�����ȱ����һЩ����!';
$strFormat = '��ʽ';
$strFullText = '��ʾ��������';
$strFunction = '����';

$strGenBy = '����';
$strGenTime = '��������';
$strGeneralRelationFeat = 'һ����ϵ����';
$strGo = '��ʼ';
$strGrants = 'Grants'; // should expressed in English
$strGzip = '"gzipped"';

$strHasBeenAltered = '�Ѿ����޸ġ�';
$strHasBeenCreated = '�Ѿ�������';
$strHaveToShow = '����Ҫѡ��������ʾһ����λ';
$strHome = '��Ŀ¼';
$strHomepageOfficial = 'phpMyAdmin �ٷ���վ';
$strHomepageSourceforge = 'phpMyAdmin ������ҳ';
$strHost = '����';
$strHostEmpty = '���������ǿյ�!';

$strIdxFulltext = 'ȫ�ļ���';
$strIfYouWish = '�����Ҫָ��������ֶΣ���ô������ö��Ÿ������ֶ��б�';
$strIgnore = '����';
$strInUse = 'ʹ����';
$strIndex = '����';
$strIndexHasBeenDropped = '���� %s �ѱ�ɾ��';
$strIndexName = '��������&nbsp;:';
$strIndexType = '��������&nbsp;:';
$strIndexes = '����';
$strInsecureMySQL = '�趨�����й��趨 (root���뼰û������) ��Ԥ��� MySQL Ȩ�޻�����ͬ�� MySQL �ŷ�������Ԥ����趨���еĻ�������ױ����֣���Ӧ�����й��趨ȥ��ֹ��ȫ©����';
$strInsert = '����';
$strInsertAsNewRow = '���һ�ʼ�¼';
$strInsertNewRow = '�����¼�¼';
$strInsertTextfiles = '���ı��ļ�����ȡ���ݣ����뵽���ݱ�';
$strInsertedRows = '��������:';
$strInstructions = 'ָʾ';
$strInvalidName = '"%s" ��һ��������,�����ܽ�������ʹ��Ϊ ���Ͽ�/���ϱ�/��λ ����.';

$strKeepPass = '�벻Ҫ��������';
$strKeyname = '����';
$strKill = 'Kill'; //should expressed in English

$strLength = '����';
$strLengthSet = '����/����*';
$strLimitNumRows = '�ʼ�¼/ÿҳ';
$strLineFeed = '���У�\\n';
$strLines = '���� ';
$strLinesTerminatedBy = '����һ�С�ʹ���ַ���';
$strLinkNotFound = '�Ҳ�������';
$strLinksTo = '���ᵽ';
$strLocationTextfile = '�ı��ļ���λ��';
$strLogPassword = '����:';
$strLogUsername = '��������:';
$strLogin = '����';
$strLogout = '�˳�ϵͳ';

$strMissingBracket = '�Ҳ�������';
$strModifications = '�޸ĺ�������Ѿ����̡�';
$strModify = '�޸�';
$strModifyIndexTopic = '�޸�����';
$strMoveTable = '�ƶ����ݱ���(��ʽΪ ���ݿ�����<b>.</b>���ݱ�����)';
$strMoveTableOK = '���ݱ� %s �Ѿ��ƶ��� %s.';
$strMySQLCharset = 'MySQL ���ֱ���';
$strMySQLReloaded = 'MySQL ����������ɡ�';
$strMySQLSaid = 'MySQL ���أ�';
$strMySQLServerProcess = 'MySQL �汾 %pma_s1% �� %pma_s2% ִ�У�������Ϊ %pma_s3%';
$strMySQLShowProcess = '��ʾ����';
$strMySQLShowStatus = '��ʾ MySQL ��������Ϣ';
$strMySQLShowVars = '��ʾ MySQL ��ϵͳ����';

$strName = '����';
$strNext = '��һ��';
$strNo = '��';
$strNoDatabases = 'û�����ݿ�';
$strNoDescription = 'û��˵��';
$strNoDropDatabases = '"DROP DATABASE" ָ���Ѿ�ͣ��.';
$strNoExplain = '�Թ�˵�� SQL';
$strNoFrames = 'phpMyAdmin ��Ϊ�ʺ�ʹ����֧��<b>ҳ��</b>�������.';
$strNoIndex = 'û���Ѷ��������!';
$strNoIndexPartsDefined = '�����������ϻ�δ����!';
$strNoModification = 'û�б��';
$strNoPassword = '��������';
$strNoPhp = '�Ƴ� PHP ��ʽ��';
$strNoPrivileges = 'û��Ȩ��';
$strNoQuery = 'û�� SQL ���!';
$strNoRights = '������û���㹻��Ȩ��!';
$strNoTablesFound = '���ݿ���û�����ݱ�';
$strNoUsersFound = '�Ҳ���ʹ����';
$strNoValidateSQL = '�Թ���� SQL';
$strNone = '������';
$strNotNumber = '�ⲻ��һ������!';
$strNotOK = 'δ��ȷ��';
$strNotSet = '<b>%s</b> ���ݱ��Ҳ�����δ�� %s �趨';
$strNotValidNumber = ' ������Ч������!';
$strNull = 'Null';
$strNumSearchResultsInTable = '%s �����Ϸ��� - ����ݱ� <i>%s</i>';
$strNumSearchResultsTotal = '<b>�ܼ�:</b> <i>%s</i> �����Ϸ���';

$strOK = 'ȷ��';
$strOftenQuotation = 'ͨ��Ϊ���š� ��ѡ�С� ��ʾʹ�����š���Ϊֻ�� char �� varchar ���͵�������Ҫ��������������';
$strOperations = '����';
$strOptimizeTable = '��ѻ����ݱ�';
$strOptionalControls = '��ѡ�����ڶ�ȡ��д��������ַ���';
$strOptionally = '����';
$strOptions = 'ѡ��';
$strOr = '��';
$strOverhead = '����';

$strPHP40203 = '����ʹ�� PHP �汾 4.2.3, ��汾��һ��˫�ֽ���Ԫ�����ش���(mbstring). ����� PHP ���汨���� 19404. phpMyAdmin ��������ʹ������汾�� PHP .';
$strPHPVersion = 'PHP �汾';
$strPageNumber = 'ҳ��:';
$strPartialText = '��ʾ��������';
$strPassword = '����';
$strPasswordEmpty = '�����ǿյ�!';
$strPasswordNotSame = '���벢����ͬ!';
$strPdfDbSchema = '"%s" ���ݿ��Ҫ - �� %s ҳ';
$strPdfInvalidPageNum = 'PDF ҳ��û���趨!';
$strPdfInvalidTblName = '���ݱ� "%s" ������!';
$strPdfNoTables = 'û�����ݱ�';
$strPhp = '���� PHP ��ʽ��';
$strPmaDocumentation = 'phpMyAdmin ˵���ı�';
$strPmaUriError = '�����趨 <tt>$cfg[\'PmaAbsoluteUri\']</tt> ���趨������!';
$strPos1 = '��ʼ';
$strPrevious = 'ǰһ��';
$strPrimary = '����';
$strPrimaryKey = '����';
$strPrimaryKeyHasBeenDropped = '�����ѱ�ɾ��';
$strPrimaryKeyName = '���������Ʊ����Ϊ PRIMARY!';
$strPrimaryKeyWarning = '("PRIMARY" <b>����</b>�������������Լ���<b>Ψһ</b>һ������!)';
$strPrint = '��ӡ';
$strPrintView = '��ӡ����';
$strPrivDescMaxConnections = 'Limits the number of new connections the user may open per hour.';
$strPrivDescMaxQuestions = 'Limits the number of queries the user may send to the server per hour.';
$strPrivDescMaxUpdates = 'Limits the number of commands that change any table or database the user may execute per hour.';
$strPrivileges = 'Ȩ��';
$strProperties = '����';
$strPutColNames = '����λ���Ʒ�������';

$strQBE = '��ѯģ��';
$strQBEDel = 'ɾ��';
$strQBEIns = '���';
$strQueryOnDb = '�����Ͽ� <b>%s</b> ִ�� SQL ���:';
$strQueryStatistics = '<b>Query statistics</b>: Since its startup, %s queries have been sent to the server.';

$strReType = '��������';
$strRecords = '��¼';
$strReferentialIntegrity = '���ָʾ������:';
$strRelationNotWorking = '��ϵ���ݱ�ĸ��ӹ���δ������, %s�밴��%s �������ԭ��.';
$strRelationView = '��ϵ����';
$strReloadFailed = 'MySQL ����ʧ�ܡ�';
$strReloadMySQL = '���� MySQL';
$strRememberReload = '����������.';
$strRenameTable = '�����ݱ����Ϊ';
$strRenameTableOK = '���ݱ� %s �����Ѿ����ó� %s��';
$strRepairTable = '�޸����ݱ�';
$strReplace = '�滻';
$strReplaceTable = '�����ݱ�������������ı��ļ��滻��';
$strReset = '����';
$strRevoke = '����';
$strRevokeGrant = '���� Grant Ȩ��';
$strRevokeGrantMessage = '���ѳ���������λʹ���ߵ� Grant Ȩ��: %s';
$strRevokeMessage = '���ѳ���������λʹ���ߵ�Ȩ��: %s';
$strRevokePriv = '����Ȩ��';
$strRowLength = '�����г���';
$strRowSize = ' �����д�С ';
$strRows = '����������';
$strRowsFrom = '�ʼ�¼����ʼ����:';
$strRowsModeHorizontal = 'ˮƽ';
$strRowsModeOptions = '��ʾΪ %s ��ʽ �� ÿ�� %s ����ʾ����';
$strRowsModeVertical = '��ֱ';
$strRowsStatistic = '������ͳ����ֵ';
$strRunQuery = 'ִ�в�ѯ';
$strRunSQLQuery = '�����ݿ� %s ִ������ָ��';
$strRunning = '������ %s';

$strSQL = 'SQL'; // should express in english
$strSQLQuery = 'SQL ���';
$strSQLResult = 'SQL ��ѯ���';
$strSave = '�洢';
$strScaleFactorSmall = '��������̫ϸ, �޷���ͼ�����һҳ��';
$strSearch = '����';
$strSearchFormTitle = '�������ݿ�';
$strSearchInTables = '��������ݱ�:';
$strSearchNeedle = 'Ѱ��֮���ֻ���ֵ (������Ԫ: "%"):';
$strSearchOption1 = '�κ�һ������';
$strSearchOption2 = '��������';
$strSearchOption3 = '��������';
$strSearchOption4 = '�Թ����ʾ�� (regular expression) ����';
$strSearchResultsFor = '���� "<i>%s</i>" �Ľ�� %s:';
$strSearchType = 'Ѱ��:';
$strSelect = 'ѡ��';
$strSelectADb = '��ѡ�����ݿ�';
$strSelectAll = 'ȫѡ';
$strSelectFields = '����ѡ��һ���ֶΣ�';
$strSelectNumRows = '��ѯ��';
$strSelectTables = 'ѡ�����ݱ�';
$strSend = '����';
$strServer = '�ŷ��� %s';
$strServerChoice = 'ѡ���ŷ���';
$strServerTrafficNotes = '<b>Server traffic</b>: These tables show the network traffic statistics of this MySQL server since its startup.';
$strServerVersion = '�ŷ����汾';
$strSetEnumVal = '����λ��ʽ�� "enum" �� "set", ��ʹ�����µĸ�ʽ����: \'a\',\'b\',\'c\'...<br />������ֵ����Ҫ���뷴б�� (\) ������ (\') , ���ټ��Ϸ�б�� (���� \'\\\\xyz\' or \'a\\\'b\').';
$strShow = '��ʾ';
$strShowAll = '��ʾȫ��';
$strShowColor = '��ʾ��ɫ';
$strShowCols = '��ʾ��';
$strShowGrid = '��ʾ���';
$strShowPHPInfo = '��ʾ PHP ��Ѷ';
$strShowTableDimension = '��ʾ����С';
$strShowTables = '��ʾ���ݱ�';
$strShowThisQuery = ' ������ʾ SQL ��� ';
$strShowingRecords = '��ʾ��¼ ';
$strSingly = '(ֻ������ʱ֮��¼)';
$strSize = '��С';
$strSort = '����';
$strSpaceUsage = '��ʹ�ÿռ�';
$strSplitWordsWithSpace = 'ÿ�������Կո� (" ") �ָ�.';
$strStatement = '����';
$strStrucCSV = 'CSV ����';
$strStrucData = '�ṹ������';
$strStrucDrop = '��� \'drop table\'';
$strStrucExcelCSV = 'Ms Excel �� CSV ��ʽ';
$strStrucOnly = 'ֻѡ��ṹ';
$strStructPropose = '�������ݱ�ṹ';
$strStructure = '�ṹ';
$strSubmit = '����';
$strSuccess = '�����е� SQL ����Ѿ��ɹ������ˡ�';
$strSum = '�ܼ�';

$strTable = '���ݱ� ';
$strTableComments = '���ݱ�ע������';
$strTableEmpty = '���ݱ������ǿյ�!';
$strTableHasBeenDropped = '���ݱ� %s �ѱ�ɾ��';
$strTableHasBeenEmptied = '���ݱ� %s �ѱ����';
$strTableHasBeenFlushed = '���ݱ� %s �ѱ�ǿ�ȸ���';
$strTableMaintenance = '���ݱ�ά��';
$strTableStructure = '���ݱ�Ľṹ';
$strTableType = '���ݱ�����';
$strTables = '%s ���ݱ�';
$strTextAreaLength = ' ���ڳ�������<br /> ����λ���ܱ༭ ';
$strTheContent = '�ļ��е������Ѿ����뵽���ݱ��С�';
$strTheContents = '�ļ��е����ݽ���ȡ�� ��ѡ�������ݱ��о��� ��ͬ��������Ψһ���� ��¼��';
$strTheTerminator = '��Щ�ֶεĽ�����';
$strTotal = '�ܼ�';
$strType = '����';

$strUncheckAll = 'ȫ��ȡ��';
$strUnique = 'Ψһ';
$strUnselectAll = 'ȫ��ȡ��';
$strUpdatePrivMessage = '���Ѿ������� %s ��Ȩ��.';
$strUpdateProfile = '��������:';
$strUpdateProfileMessage = '���ϼ�������.';
$strUpdateQuery = '�������';
$strUsage = 'ʹ��';
$strUseBackquotes = '�������ݱ���λʹ������';
$strUseTables = 'ʹ�����ݱ�';
$strUser = 'ʹ����';
$strUserEmpty = 'ʹ���������ǿյ�!';
$strUserName = 'ʹ��������';
$strUsers = 'ʹ����';

$strValidateSQL = '��� SQL';
$strValidatorError = 'SQL ������ʽδ�������������Ƿ��ѽ� %s�ı�%s �ڵ� PHP ������װ��';
$strValue = 'ֵ';
$strViewDump = '�鿴���ݱ�Ľṹ��ժҪ��Ϣ��';
$strViewDumpDB = '�鿴���ݿ�Ľṹ��ժҪ��Ϣ��';

$strWebServerUploadDirectory = 'Web �ŷ�������Ŀ¼';
$strWebServerUploadDirectoryError = '�趨֮����Ŀ¼����δ��ʹ��';
$strWelcome = '��ӭʹ�� %s';
$strWithChecked = 'ѡ������ݱ�';
$strWrongUser = '������󣬷��ʱ��ܾ���';

$strYes = '��';

$strZip = '"zipped"';
// To translate
$timespanfmt = '%s days, %s hours, %s minutes and %s seconds'; //to translate

$strAbortedClients = 'Aborted'; //to translate
$strAdministration = 'Administration'; //to translate

$strBeginCut = 'BEGIN CUT'; //to translate
$strBeginRaw = 'BEGIN RAW'; //to translate
$strBzError = 'phpMyAdmin was unable to compress the dump because of a broken Bz2 extension in this php version. It is strongly recommended to set the <code>$cfg[\'BZipDump\']</code> directive in your phpMyAdmin configuration file to <code>FALSE</code>. If you want to use the Bz2 compression features, you should upgrade to a later php version. See php bug report %s for details.'; //to translate

$strCannotLogin = 'Cannot login to MySQL server';  //to translate
$strCharsetOfFile = 'Character set of the file:'; //to translate
$strCommand = 'Command'; //to translate
$strConnections = 'Connections'; //to translate
$strCouldNotKill = 'phpMyAdmin was unable to kill thread %s. It probably has already been closed.'; //to translate

$strDeleteAndFlush = 'Delete the users and reload the privileges afterwards.'; //to translate
$strDeleteAndFlushDescr = 'This is the cleanest way, but reloading the privileges may take a while.'; //to translate
$strDeleting = 'Deleting %s'; //to translate

$strEndCut = 'END CUT'; //to translate
$strEndRaw = 'END RAW'; //to translate

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

$strSQLParserBugMessage = 'There is a chance that you may have found a bug in the SQL parser. Please examine your query closely, and check that the quotes are correct and not mis-matched. Other possible failure causes may be that you are uploading a file with binary outside of a quoted text area. You can also try your query on the MySQL command line interface. The MySQL server error output below, if there is any, may also help you in diagnosing the problem. If you still have problems or if the parser fails where the command line interface succeeds, please reduce your SQL query input to the single query that causes problems, and submit a bug report with the data chunk in the CUT section below:'; //to translate
$strSQLParserUserError = 'There seems to be an error in your SQL query. The MySQL server error output below, if there is any, may also help you in diagnosing the problem'; //to translate
$strSQPBugInvalidIdentifer = 'Invalid Identifer'; //to translate
$strSQPBugUnclosedQuote = 'Unclosed quote'; //to translate
$strSQPBugUnknownPunctuation = 'Unknown Punctuation String'; //to translate
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
