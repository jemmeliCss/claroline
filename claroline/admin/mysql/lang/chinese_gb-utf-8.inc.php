<?php
/* $Id$ */

/**
 * Last translation by: Siu Sun <siusun@best-view.net>
 */

$charset = 'utf-8';
$allow_recoding = TRUE;
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

$strAPrimaryKey = '主键已经添加到 %s';
$strAccessDenied = '访问被拒绝';
$strAction = '执行操作';
$strAddDeleteColumn = '添加/删除 选择栏';
$strAddDeleteRow = '添加/删除 筛选列';
$strAddNewField = '添加新字段';
$strAddPriv = '添加新权限';
$strAddPrivMessage = '您已经为下此使用者添加了新权限.';
$strAddSearchConditions = '添加检索条件 ("where" 语句的主体)：';
$strAddToIndex = '添加 &nbsp;%s&nbsp; 组索引栏';
$strAddUser = '添增使用者';
$strAddUserMessage = '您已添增了一个新使用者.';
$strAffectedRows = '影响列数:';
$strAfter = '在 %s 之后';
$strAfterInsertBack = '返回';
$strAfterInsertNewInsert = '添加一笔记录';
$strAll = '全部';
$strAllTableSameWidth = '以相同宽度显示所有数据表?';
$strAlterOrderBy = '根据栏位内容排序记录';
$strAnIndex = '索引已经添加到 %s';
$strAnalyzeTable = '分析数据表';
$strAnd = '与';
$strAny = '任何';
$strAnyColumn = '任何栏位';
$strAnyDatabase = '任何数据库';
$strAnyHost = '任何主机';
$strAnyTable = '任何数据表';
$strAnyUser = '任何使用者';
$strAscending = '递增';
$strAtBeginningOfTable = '于数据表开头';
$strAtEndOfTable = '于数据表尾端';
$strAttr = '属性';

$strBack = '返回';
$strBinary = ' 二进制码 ';
$strBinaryDoNotEdit = ' 二进制码 - 无法编辑 ';
$strBookmarkDeleted = '标签已经删除.';
$strBookmarkLabel = '标签名称';
$strBookmarkQuery = 'SQL 语法书签';
$strBookmarkThis = '加入此 SQL 语法到书签';
$strBookmarkView = '查看';
$strBrowse = '浏览';
$strBzip = '"bzipped"';

$strCantLoadMySQL = '不能载入 MySQL 附件,<br />请检查 PHP 的组态设定';
$strCantLoadRecodeIconv = '未能读取 iconv 或重新编码程式来作文字编码转换, 请设定 php 来启动这些模组或取消 phpMyAdmin 使用文字编码转换功能.';
$strCantRenameIdxToPrimary = '无法将索引更名为 PRIMARY!';
$strCantUseRecodeIconv = '当文编码模组读取後,未能使用 iconv 、 libiconv 或 recode_string 功能. 请检查您的 php 设定.';
$strCardinality = '组别';
$strCarriage = '回车: \\r';
$strChange = '改变';
$strChangeDisplay = '选择显示之字段';
$strChangePassword = '更改密码';
$strCheckAll = '全选';
$strCheckDbPriv = '检查数据库权限';
$strCheckTable = '检查数据表';
$strChoosePage = '请选择需要编辑的页码';
$strColComFeat = '显示栏位注解';
$strColumn = '栏位';
$strColumnNames = '栏位名';
$strComments = '注解';
$strCompleteInserts = '使用完整添增指令';
$strCompression = '压缩';
$strConfigFileError = 'phpMyAdmin 未能读取您的设定档! 这可能是因为 php 找到语法上的错误或 php 未能找到档案而成.<br />请尝试直接按下下方的连结开启并查看 php 的错误信息. 通常的错误都来自某处漏了引号或分别.<br />如果按下连结後出现空白页, 即代表没有任何问题.';
$strConfigureTableCoord = '请设定数据表 %s 内的坐标';
$strConfirm = '您确定要这样做?';
$strCookiesRequired = 'Cookies 必须启动才能登入.';
$strCopyTable = '复制数据表到： (格式为 数据库名称<b>.</b>数据表名称):';
$strCopyTableOK = '数据表 %s 已经成功复制为 %s。';
$strCreate = '建立';
$strCreateIndex = '新增 &nbsp;%s&nbsp; 组索引栏';
$strCreateIndexTopic = '添加一组索引';
$strCreateNewDatabase = '建立一个新的数据库';
$strCreateNewTable = '建立一个新的数据表与数据库 %s';
$strCreatePage = '建立新一页';
$strCreatePdfFeat = '建立 PDF';
$strCriteria = '规范';

$strData = '数据';
$strDataDict = '数据字典';
$strDataOnly = '只有数据';
$strDatabase = '数据库 ';
$strDatabaseHasBeenDropped = '数据库 %s 已被删除';
$strDatabaseWildcard = '数据库 (允许使用万用字符):';
$strDatabases = '数据库';
$strDatabasesStats = '数据库统计';
$strDefault = '缺省值';
$strDelete = '删除';
$strDeleteFailed = '删除失败!';
$strDeleteUserMessage = '您已经将用户 %s 删除.';
$strDeleted = '该记录已经被删除。';
$strDeletedRows = '已删除栏数:';
$strDescending = '递减';
$strDisabled = '未启动';
$strDisplay = '显示';
$strDisplayFeat = '功能显示';
$strDisplayOrder = '显示次序';
$strDisplayPDF = '显示 PDF 图表';
$strDoAQuery = '请执行 "查询示例" (通配符: "%")';
$strDoYouReally = '请确认要 ';
$strDocu = '文档';
$strDrop = '丢弃';
$strDropDB = '丢弃数据库 %s';
$strDropTable = '删除数据表';
$strDumpXRows = '备份 %s 列, 由 %s 列开始.';
$strDumpingData = '导出下面的数据库内容';
$strDynamic = '动态';

$strEdit = '编辑';
$strEditPDFPages = '编辑 PDF 页码';
$strEditPrivileges = '编辑权限';
$strEffective = '有效';
$strEmpty = '清空';
$strEmptyResultSet = 'MySQL 返回的查询结果为空。 (原因可能为：没有找到符合条件的记录。)';
$strEnabled = '启动';
$strEnd = '结束';
$strEnglishPrivileges = ' 注意: MySQL 权限名称会被解释成英文 ';
$strError = '错误';
$strExplain = '说明 SQL';
$strExport = '输出';
$strExportToXML = '输出到 XML 格式';
$strExtendedInserts = '伸延添加模式';
$strExtra = '额外';

$strField = '字段';
$strFieldHasBeenDropped = '数据表 %s 已被删除';
$strFields = '字段';
$strFieldsEmpty = ' 栏位总数是空的! ';
$strFieldsEnclosedBy = '「栏位」使用字元：';
$strFieldsEscapedBy = '「ESCAPE」使用字元：';
$strFieldsTerminatedBy = '「栏位分隔」使用字元：';
$strFixed = '固定';
$strFlushTable = '强迫更新资料表 ("FLUSH")';
$strFormEmpty = '表格内缺少了一些资料!';
$strFormat = '格式';
$strFullText = '显示完整文字';
$strFunction = '功能';

$strGenBy = '建立';
$strGenTime = '建立日期';
$strGeneralRelationFeat = '一般联系功能';
$strGo = '开始';
$strGrants = 'Grants'; // should expressed in English
$strGzip = '"gzipped"';

$strHasBeenAltered = '已经被修改。';
$strHasBeenCreated = '已经建立。';
$strHaveToShow = '您需要选择最少显示一行栏位';
$strHome = '主目录';
$strHomepageOfficial = 'phpMyAdmin 官方网站';
$strHomepageSourceforge = 'phpMyAdmin 下载网页';
$strHost = '主机';
$strHostEmpty = '主机名称是空的!';

$strIdxFulltext = '全文检索';
$strIfYouWish = '如果你要指定调入的字段，那么请给出用逗号隔开的字段列表。';
$strIgnore = '忽略';
$strInUse = '使用中';
$strIndex = '索引';
$strIndexHasBeenDropped = '索引 %s 已被删除';
$strIndexName = '索引名称&nbsp;:';
$strIndexType = '索引类型&nbsp;:';
$strIndexes = '索引';
$strInsecureMySQL = '设定档内有关设定 (root登入及没有密码) 与预设的 MySQL 权限户口相同。 MySQL 伺服器在这预设的设定运行的话会很容易被入侵，您应更改有关设定去防止安全漏洞。';
$strInsert = '插入';
$strInsertAsNewRow = '添加一笔记录';
$strInsertNewRow = '插入新记录';
$strInsertTextfiles = '从文本文件中提取数据，插入到数据表：';
$strInsertedRows = '新增列数:';
$strInstructions = '指示';
$strInvalidName = '"%s" 是一个保留字,您不能将保留字使用为 资料库/资料表/栏位 名称.';

$strKeepPass = '请不要更改密码';
$strKeyname = '键名';
$strKill = 'Kill'; //should expressed in English

$strLength = '长度';
$strLengthSet = '长度/集合*';
$strLimitNumRows = '笔记录/每页';
$strLineFeed = '换行：\\n';
$strLines = '行数 ';
$strLinesTerminatedBy = '「下一行」使用字符：';
$strLinkNotFound = '找不到连结';
$strLinksTo = '连结到';
$strLocationTextfile = '文本文件的位置';
$strLogPassword = '密码:';
$strLogUsername = '登入名称:';
$strLogin = '登入';
$strLogout = '退出系统';

$strMissingBracket = '找不到括号';
$strModifications = '修改后的数据已经存盘。';
$strModify = '修改';
$strModifyIndexTopic = '修改索引';
$strMoveTable = '移动数据表到：(格式为 数据库名称<b>.</b>数据表名称)';
$strMoveTableOK = '数据表 %s 已经移动到 %s.';
$strMySQLCharset = 'MySQL 文字编码';
$strMySQLReloaded = 'MySQL 重新启动完成。';
$strMySQLSaid = 'MySQL 返回：';
$strMySQLServerProcess = 'MySQL 版本 %pma_s1% 在 %pma_s2% 执行，登入者为 %pma_s3%';
$strMySQLShowProcess = '显示进程';
$strMySQLShowStatus = '显示 MySQL 的运行信息';
$strMySQLShowVars = '显示 MySQL 的系统变量';

$strName = '名字';
$strNext = '下一个';
$strNo = '否';
$strNoDatabases = '没有数据库';
$strNoDescription = '没有说明';
$strNoDropDatabases = '"DROP DATABASE" 指令已经停用.';
$strNoExplain = '略过说明 SQL';
$strNoFrames = 'phpMyAdmin 较为适合使用在支持<b>页框</b>的浏览器.';
$strNoIndex = '没有已定义的索引!';
$strNoIndexPartsDefined = '部份索引资料还未定义!';
$strNoModification = '没有变更';
$strNoPassword = '不用密码';
$strNoPhp = '移除 PHP 程式码';
$strNoPrivileges = '没有权限';
$strNoQuery = '没有 SQL 语句!';
$strNoRights = '您现在没有足够的权限!';
$strNoTablesFound = '数据库中没有数据表。';
$strNoUsersFound = '找不到使用者';
$strNoValidateSQL = '略过检查 SQL';
$strNone = '不适用';
$strNotNumber = '这不是一个数字!';
$strNotOK = '未能确定';
$strNotSet = '<b>%s</b> 数据表找不到或还未在 %s 设定';
$strNotValidNumber = ' 不是有效的列数!';
$strNull = 'Null';
$strNumSearchResultsInTable = '%s 项资料符合 - 於数据表 <i>%s</i>';
$strNumSearchResultsTotal = '<b>总计:</b> <i>%s</i> 项资料符合';

$strOK = '确定';
$strOftenQuotation = '通常为引号。 ＂选中“ 表示使用引号。因为只有 char 和 varchar 类型的数据需要用引号括起来。';
$strOperations = '操作';
$strOptimizeTable = '最佳化数据表';
$strOptionalControls = '可选。用于读取或写入特殊的字符。';
$strOptionally = '随意';
$strOptions = '选项';
$strOr = '或';
$strOverhead = '多余';

$strPHP40203 = '您正使用 PHP 版本 4.2.3, 这版本有一个双字节字元的严重错误(mbstring). 请参阅 PHP 臭虫报告编号 19404. phpMyAdmin 并不建议使用这个版本的 PHP .';
$strPHPVersion = 'PHP 版本';
$strPageNumber = '页码:';
$strPartialText = '显示部份文字';
$strPassword = '密码';
$strPasswordEmpty = '密码是空的!';
$strPasswordNotSame = '密码并非相同!';
$strPdfDbSchema = '"%s" 数据库概要 - 第 %s 页';
$strPdfInvalidPageNum = 'PDF 页码没有设定!';
$strPdfInvalidTblName = '数据表 "%s" 不存在!';
$strPdfNoTables = '没有数据表';
$strPhp = '建立 PHP 程式码';
$strPmaDocumentation = 'phpMyAdmin 说明文本';
$strPmaUriError = '必须设定 <tt>$cfg[\'PmaAbsoluteUri\']</tt> 在设定档案内!';
$strPos1 = '开始';
$strPrevious = '前一个';
$strPrimary = '键名';
$strPrimaryKey = '主键';
$strPrimaryKeyHasBeenDropped = '主键已被删除';
$strPrimaryKeyName = '主键的名称必须称为 PRIMARY!';
$strPrimaryKeyWarning = '("PRIMARY" <b>必须</b>是主键的名称以及是<b>唯一</b>一组主键!)';
$strPrint = '列印';
$strPrintView = '打印检视';
$strPrivDescMaxConnections = 'Limits the number of new connections the user may open per hour.';
$strPrivDescMaxQuestions = 'Limits the number of queries the user may send to the server per hour.';
$strPrivDescMaxUpdates = 'Limits the number of commands that change any table or database the user may execute per hour.';
$strPrivileges = '权限';
$strProperties = '属性';
$strPutColNames = '将栏位名称放在首列';

$strQBE = '查询模板';
$strQBEDel = '删除';
$strQBEIns = '添加';
$strQueryOnDb = '在资料库 <b>%s</b> 执行 SQL 语句:';
$strQueryStatistics = '<b>Query statistics</b>: Since its startup, %s queries have been sent to the server.';

$strReType = '重新输入';
$strRecords = '记录';
$strReferentialIntegrity = '检查指示完整性:';
$strRelationNotWorking = '联系数据表的附加功能未能启动, %s请按此%s 查出问题原因.';
$strRelationView = '联系检视';
$strReloadFailed = 'MySQL 重起失败。';
$strReloadMySQL = '重起 MySQL';
$strRememberReload = '请重新启动.';
$strRenameTable = '将数据表改名为';
$strRenameTableOK = '数据表 %s 名字已经被该成 %s。';
$strRepairTable = '修复数据表';
$strReplace = '替换';
$strReplaceTable = '将数据表的数据用以下文本文件替换：';
$strReset = '重置';
$strRevoke = '撤回';
$strRevokeGrant = '撤回 Grant 权限';
$strRevokeGrantMessage = '您已撤回以下这位使用者的 Grant 权限: %s';
$strRevokeMessage = '您已撤回下面这位使用者的权限: %s';
$strRevokePriv = '撤回权限';
$strRowLength = '数据列长度';
$strRowSize = ' 数据列大小 ';
$strRows = '数据列列数';
$strRowsFrom = '笔记录，开始列数:';
$strRowsModeHorizontal = '水平';
$strRowsModeOptions = '显示为 %s 方式 及 每隔 %s 行显示栏名';
$strRowsModeVertical = '垂直';
$strRowsStatistic = '数据列统计数值';
$strRunQuery = '执行查询';
$strRunSQLQuery = '在数据库 %s 执行以下指令';
$strRunning = '运行于 %s';

$strSQL = 'SQL'; // should express in english
$strSQLQuery = 'SQL 语句';
$strSQLResult = 'SQL 查询结果';
$strSave = '存储';
$strScaleFactorSmall = '比例倍数太细, 无法将图表放在一页内';
$strSearch = '搜索';
$strSearchFormTitle = '搜索数据库';
$strSearchInTables = '於以下数据表:';
$strSearchNeedle = '寻找之文字或数值 (万用字元: "%"):';
$strSearchOption1 = '任何一组文字';
$strSearchOption2 = '所有文字';
$strSearchOption3 = '完整词语';
$strSearchOption4 = '以规则表示法 (regular expression) 搜索';
$strSearchResultsFor = '搜索 "<i>%s</i>" 的结果 %s:';
$strSearchType = '寻找:';
$strSelect = '选择';
$strSelectADb = '请选择数据库';
$strSelectAll = '全选';
$strSelectFields = '至少选择一个字段：';
$strSelectNumRows = '查询中';
$strSelectTables = '选择数据表';
$strSend = '发送';
$strServer = '伺服器 %s';
$strServerChoice = '选择伺服机';
$strServerTrafficNotes = '<b>Server traffic</b>: These tables show the network traffic statistics of this MySQL server since its startup.';
$strServerVersion = '伺服机版本';
$strSetEnumVal = '如栏位格式是 "enum" 或 "set", 请使用以下的格式输入: \'a\',\'b\',\'c\'...<br />如在数值上需要输入反斜线 (\) 或单引号 (\') , 请再加上反斜线 (例如 \'\\\\xyz\' or \'a\\\'b\').';
$strShow = '显示';
$strShowAll = '显示全部';
$strShowColor = '显示颜色';
$strShowCols = '显示栏';
$strShowGrid = '显示框格';
$strShowPHPInfo = '显示 PHP 资讯';
$strShowTableDimension = '显示表格大小';
$strShowTables = '显示数据表';
$strShowThisQuery = ' 重新显示 SQL 语句 ';
$strShowingRecords = '显示记录 ';
$strSingly = '(只排序现时之记录)';
$strSize = '大小';
$strSort = '排序';
$strSpaceUsage = '已使用空间';
$strSplitWordsWithSpace = '每组文字以空格 (" ") 分隔.';
$strStatement = '叙述';
$strStrucCSV = 'CSV 数据';
$strStrucData = '结构和数据';
$strStrucDrop = '添加 \'drop table\'';
$strStrucExcelCSV = 'Ms Excel 的 CSV 格式';
$strStrucOnly = '只选择结构';
$strStructPropose = '分析数据表结构';
$strStructure = '结构';
$strSubmit = '发送';
$strSuccess = '你运行的 SQL 语句已经成功运行了。';
$strSum = '总计';

$strTable = '数据表 ';
$strTableComments = '数据表注解文字';
$strTableEmpty = '数据表名称是空的!';
$strTableHasBeenDropped = '数据表 %s 已被删除';
$strTableHasBeenEmptied = '数据表 %s 已被清空';
$strTableHasBeenFlushed = '数据表 %s 已被强迫更新';
$strTableMaintenance = '数据表维护';
$strTableStructure = '数据表的结构';
$strTableType = '数据表类型';
$strTables = '%s 数据表';
$strTextAreaLength = ' 由于长度限制<br /> 此栏位不能编辑 ';
$strTheContent = '文件中的内容已经插入到数据表中。';
$strTheContents = '文件中的内容将会取代 所选定的数据表中具有 相同的主键或唯一键的 记录。';
$strTheTerminator = '这些字段的结束符';
$strTotal = '总计';
$strType = '类型';

$strUncheckAll = '全部取消';
$strUnique = '唯一';
$strUnselectAll = '全部取消';
$strUpdatePrivMessage = '您已经更新了 %s 的权限.';
$strUpdateProfile = '更新资料:';
$strUpdateProfileMessage = '资料己经更新.';
$strUpdateQuery = '更新语句';
$strUsage = '使用';
$strUseBackquotes = '请在数据表及栏位使用引号';
$strUseTables = '使用数据表';
$strUser = '使用者';
$strUserEmpty = '使用者名称是空的!';
$strUserName = '使用者名称';
$strUsers = '使用者';

$strValidateSQL = '检查 SQL';
$strValidatorError = 'SQL 分析程式未能启动，请检查是否已将 %s文本%s 内的 PHP 档案安装。';
$strValue = '值';
$strViewDump = '查看数据表的结构和摘要信息。';
$strViewDumpDB = '查看数据库的结构和摘要信息。';

$strWebServerUploadDirectory = 'Web 伺服器上载目录';
$strWebServerUploadDirectoryError = '设定之上载目录错误，未能使用';
$strWelcome = '欢迎使用 %s';
$strWithChecked = '选择的数据表：';
$strWrongUser = '密码错误，访问被拒绝。';

$strYes = '是';

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
