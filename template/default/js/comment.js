function CommentCheckForm(){if(document.CommentForm.content.value==""){alert('请填写您的评论内容');document.CommentForm.content.focus();return false;}
if(document.CommentForm.checkcode.value==""){alert('请填写验证码！');document.CommentForm.checkcode.focus();return false;}
document.CommentForm.mymps.disabled=true;document.CommentForm.mymps.value="请稍候";return true;}
function ifnonmember(){if(document.CommentForm.nonmember.checked==true){document.CommentForm.userid.disabled=true;document.CommentForm.userpwd.disabled=true;document.CommentForm.userid.value='游客';document.CommentForm.userpwd.value='123456';}else{document.CommentForm.userid.disabled=false;document.CommentForm.userpwd.disabled=false;document.CommentForm.userid.value='';document.CommentForm.userpwd.value='';}}