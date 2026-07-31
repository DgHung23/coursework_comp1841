from pathlib import Path

import pythoncom
import win32com.client


DOCX = Path(r"C:\xampp\htdocs\COMP1841\CourseWork\COMP1841_Coursework_Report.docx")
PDF = Path(r"C:\xampp\htdocs\COMP1841\CourseWork\scratch\rendered_report\report.pdf")


def main():
    PDF.parent.mkdir(parents=True, exist_ok=True)
    pythoncom.CoInitialize()
    word = win32com.client.DispatchEx("Word.Application")
    word.Visible = False
    word.DisplayAlerts = 0
    word.Options.UpdateLinksAtOpen = False
    doc = word.Documents.Open(str(DOCX), ReadOnly=True, ConfirmConversions=False, AddToRecentFiles=False)
    doc.SaveAs2(str(PDF), FileFormat=17)
    doc.Close(False)
    word.Quit()


if __name__ == "__main__":
    main()
