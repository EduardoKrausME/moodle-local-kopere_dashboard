<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.
/**
 * @package    local_kopere_dashboard
 * @copyright  2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
define ( 'lang_Select', '选择' );
define ( 'lang_Erase', '删除' );
define ( 'lang_Open', '打开' );
define ( 'lang_Confirm_del', '确定删除此文件?' );
define ( 'lang_All', '所有' );
define ( 'lang_Files', '文件' );
define ( 'lang_Images', '图片' );
define ( 'lang_Archives', '存档' );
define ( 'lang_Error_Upload', '上传的文件超过了允许的最大尺寸' );
define ( 'lang_Error_extension', '此类文件不被支持' );
define ( 'lang_Upload_file', '上传' );
define ( 'lang_Filters', '过滤' );
define ( 'lang_Videos', '视频' );
define ( 'lang_Music', '音乐' );
define ( 'lang_New_Folder', '新文件夹' );
define ( 'lang_Folder_Created', '文件夹创建成功' );
define ( 'lang_Existing_Folder', '文件夹已经存在' );
define ( 'lang_Confirm_Folder_del', '确定删除此文件夹和里面的所有文件?' );
define ( 'lang_Return_Files_List', '返回文件列表' );
define ( 'lang_Preview', '预览' );
define ( 'lang_Download', '下载' );
define ( 'lang_Insert_Folder_Name', '填写文件夹名称:' );
define ( 'lang_Root', 'root' );
define ( 'lang_Rename', '改名' );
define ( 'lang_Back', '返回' );
define ( 'lang_View', '视图' );
define ( 'lang_View_list', '列表视图' );
define ( 'lang_View_columns_list', '列视图' );
define ( 'lang_View_boxes', '方块视图' );
define ( 'lang_Toolbar', '工具栏' );
define ( 'lang_Actions', '操作' );
define ( 'lang_Rename_existing_file', '文件已存在' );
define ( 'lang_Rename_existing_folder', '文件夹已存在' );
define ( 'lang_Empty_name', '请输入文件名' );
define ( 'lang_Text_filter', '文字过滤' );
define ( 'lang_Swipe_help', '在文件或文件夹的名称上划过已显示更多选项' );
define ( 'lang_Upload_base', '普通上传' );
define ( 'lang_Upload_java', 'JAVA上传(适用于大文件上传)' );
define ( 'lang_Upload_java_help', '如果Java Applet没有加载，你可以：1. 请确认你的电脑安装了Java。如果没有安装，在这里下载安装 <a href=\'http://java.com/en/download/\'>[下载链接]</a>   2. 确保您电脑的防火墙没有阻挡Java上传' );
define ( 'lang_Upload_base_help', '将需要上传的文件拖动到以上区域 (需要比较新的浏览器才支持)，并选择相关文件。当上传完成，点击\'返回文件列表\'按钮' );
define ( 'lang_Type_dir', 'dir' );
define ( 'lang_Type', '类型' );
define ( 'lang_Dimension', '尺寸' );
define ( 'lang_Size', '大小' );
define ( 'lang_Date', '日期' );
define ( 'lang_Filename', '文件名' );
define ( 'lang_Operations', '操作' );
define ( 'lang_Date_type', 'y-m-d' );
define ( 'lang_OK', 'OK' );
define ( 'lang_Cancel', '取消' );
define ( 'lang_Sorting', '排序' );
define ( 'lang_Show_url', '显示URL' );
define ( 'lang_Extract', '解压缩到这里' );
define ( 'lang_File_info', '文件信息' );
define ( 'lang_Edit_image', '编辑图片' );
define ( 'lang_Duplicate', '复制' );
define ( 'lang_Folders', '文件夹' );
define ( 'lang_Copy', '拷贝' );
define ( 'lang_Cut', '剪切' );
define ( 'lang_Paste', '粘贴' );
define ( 'lang_CB', '粘贴板' ); // clipboard
define ( 'lang_Paste_Here', '粘贴到这个目录' );
define ( 'lang_Paste_Confirm', '确定粘贴到这个目录? 这有可能会覆盖已经存在的文件或文件夹' );
define ( 'lang_Paste_Failed', '文件粘贴失败' );
define ( 'lang_Clear_Clipboard', '清除粘贴板' );
define ( 'lang_Clear_Clipboard_Confirm', '确定清除粘贴板?' );
define ( 'lang_Files_ON_Clipboard', '粘贴板上还有文件存在' );
define ( 'lang_Copy_Cut_Size_Limit', '无法 %s 选择的文件，选择的文件太大，超过了允许的大小: %d MB' ); // %s = cut or copy
define ( 'lang_Copy_Cut_Count_Limit', '无法 %s 选择的文件，您选择的文件和文件夹数目超过限制: %d 个文件' ); // %s = cut or copy
define ( 'lang_Copy_Cut_Not_Allowed', ' 您没有权限 %s 文件' ); // %s(1) = cut or copy, %s(2) = files or folders
define ( 'lang_Aviary_No_Save', '无法保存图片' );
define ( 'lang_Zip_No_Extract', '文件解压缩失败。文件可能已经损坏' );
define ( 'lang_Zip_Invalid', '不支持此文件后缀，支持的后缀名: zip, gz, tar.' );
define ( 'lang_Dir_No_Write', '您选择的目录没有写权限' );
define ( 'lang_Function_Disabled', '%s 功能已经被服务器禁用。' ); // %s = cut or copy
define ( 'lang_File_Permission', 'File permission' );
define ( 'lang_File_Permission_Not_Allowed', 'Changing %s permissions are not allowed.' ); // %s = files or folders
define ( 'lang_File_Permission_Recursive', 'Apply recursively?' );
define ( 'lang_File_Permission_Wrong_Mode', 'The supplied permission mode is incorrect.' );
define ( 'lang_User', 'User' );
define ( 'lang_Group', 'Group' );
define ( 'lang_Yes', 'Yes' );
define ( 'lang_No', 'No' );
define ( 'lang_Lang_Not_Found', 'Could not find language.' );
define ( 'lang_Lang_Change', 'Change the language' );
define ( 'lang_File_Not_Found', 'Could not find the file.' );
define ( 'lang_File_Open_Edit_Not_Allowed', 'You are not allowed to %s this file.' ); // %s = open or edit
define ( 'lang_Edit', 'Edit' );
define ( 'lang_Edit_File', 'Edit file\'s content' );
define ( 'lang_File_Save_OK', 'File successfully saved.' );
define ( 'lang_File_Save_Error', 'There was an error while saving the file.' );
define ( 'lang_New_File', 'New File' );
define ( 'lang_No_Extension', 'You have to add a file extension.' );
define ( 'lang_Valid_Extensions', 'Valid extensions: %s' ); // %s = txt,log etc.
define ( 'lang_Upload_message', 'Drop file here to upload' );
define ( 'lang_SERVER ERROR', 'SERVER ERROR' );
define ( 'lang_forbiden', 'Forbiden' );
define ( 'lang_wrong path', 'Wrong path' );
define ( 'lang_wrong name', 'Wrong name' );
define ( 'lang_wrong extension', 'Wrong extension' );
define ( 'lang_wrong option', 'Wrong option' );
define ( 'lang_wrong data', 'Wrong data' );
define ( 'lang_wrong action', 'Wrong action' );
define ( 'lang_wrong sub-action', 'Wrong sub-actio' );
define ( 'lang_no action passed', 'No action passed' );
define ( 'lang_no path', 'No path' );
define ( 'lang_no file', 'No file' );
define ( 'lang_view type number missing', 'View type number missing' );
define ( 'lang_Not enought Memory', 'Not enought Memory' );
define ( 'lang_max_size_reached', 'Your image folder has reach its maximale size of %d MB.' ); //%d = max overall size
define ( 'lang_B', 'B' );
define ( 'lang_KB', 'KB' );
define ( 'lang_MB', 'MB' );
define ( 'lang_GB', 'GB' );
define ( 'lang_TB', 'TB' );
define ( 'lang_total size', 'Total size' );
