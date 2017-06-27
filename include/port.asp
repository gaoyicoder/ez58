<%
response.write Request.ServerVariables("REMOTE_PORT")
'response.Redirect("a.php?port="+Request.ServerVariables("REMOTE_PORT"))
%>