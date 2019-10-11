//
//  RegistrationController.swift
//  Light Community
//
//  Created by Samuel Clauzon on 11/06/2019.
//  Copyright © 2019 Samuel Clauzon. All rights reserved.
//

import UIKit
import CoreData

class RegistrationController: UIViewController, UIScrollViewDelegate, UITextFieldDelegate {
    
    @IBOutlet weak var scrollView: UIScrollView!
    
    @IBOutlet weak var contentView: UIView!
    
    @IBOutlet weak var maleSexChoice: UIView!
    
    @IBOutlet weak var femaleSexChoice: UIView!
    
    @IBOutlet weak var otherSexChoice: UIView!
    
    @IBOutlet weak var isManSexChoose: UIImageView!
   
    @IBOutlet weak var isFemaleSexChoose: UIImageView!
    
    @IBOutlet weak var isAnotherSexChoice: UIImageView!
    
    @IBOutlet weak var registrationButton: UIButton!
    
    @IBOutlet weak var lastNameView: UIView!
    
    @IBOutlet weak var firstNameView: UIView!
    
    @IBOutlet weak var emailView: UIView!
    
    @IBOutlet weak var passwordView: UIView!
    
    @IBOutlet weak var passwordRepeatView: UIView!
    
    @IBOutlet weak var lastNameField: UITextField!
    
    @IBOutlet weak var firstNameField: UITextField!
    
    @IBOutlet weak var emailField: UITextField!
    
    @IBOutlet weak var passwordField: UITextField!
    
    @IBOutlet weak var passwordRepeatField: UITextField!
    
    @IBOutlet weak var lastNameFieldState: UIImageView!
    
    @IBOutlet weak var firstNameFieldState: UIImageView!
    
    @IBOutlet weak var emailFieldState: UIImageView!
    
    @IBOutlet weak var passwordFieldState: UIImageView!
    
    @IBOutlet weak var passwordRepeatFieldState: UIImageView!
    
    @IBOutlet weak var loadingView: UIView!
    
    @IBOutlet weak var loadingIndicator: UIActivityIndicatorView!
    
    @IBOutlet weak var errorView: UIView!
    
    @IBOutlet weak var errorViewContent: UILabel!
    
    
    override func viewDidLoad() {
        super.viewDidLoad()
        
        Registration.sexChoice = .noSex
        Registration.isValidRegistration = false
        
        lastNameField.delegate = self
        firstNameField.delegate = self
        emailField.delegate = self
        passwordField.delegate = self
        passwordRepeatField.delegate = self
        
        loadingView.isHidden = true
        loadingIndicator.isHidden = true
        
        errorView.layer.cornerRadius = 5.0
        errorView.layer.borderWidth = 1.0
        errorView.layer.borderColor = #colorLiteral(red: 0, green: 0, blue: 0, alpha: 1)
        
        lastNameView.layer.cornerRadius = 5.0
        lastNameView.layer.borderWidth = 1.0
        lastNameView.layer.borderColor = #colorLiteral(red: 0, green: 0, blue: 0, alpha: 1)
        firstNameView.layer.cornerRadius = 5.0
        firstNameView.layer.borderWidth = 1.0
        firstNameView.layer.borderColor = #colorLiteral(red: 0, green: 0, blue: 0, alpha: 1)
        emailView.layer.cornerRadius = 5.0
        emailView.layer.borderWidth = 1.0
        emailView.layer.borderColor = #colorLiteral(red: 0, green: 0, blue: 0, alpha: 1)
        passwordView.layer.cornerRadius = 5.0
        passwordView.layer.borderWidth = 1.0
        passwordView.layer.borderColor = #colorLiteral(red: 0, green: 0, blue: 0, alpha: 1)
        passwordRepeatView.layer.cornerRadius = 5.0
        passwordRepeatView.layer.borderWidth = 1.0
        passwordRepeatView.layer.borderColor = #colorLiteral(red: 0, green: 0, blue: 0, alpha: 1)
        
        isManSexChoose.isHidden = true
        isFemaleSexChoose.isHidden = true
        isAnotherSexChoice.isHidden = true
        
        maleSexChoice.layer.cornerRadius = 5.0
        maleSexChoice.layer.borderWidth = 1.0
        maleSexChoice.layer.borderColor = #colorLiteral(red: 1.0, green: 1.0, blue: 1.0, alpha: 1.0)
        
        femaleSexChoice.layer.cornerRadius = 5.0
        femaleSexChoice.layer.borderWidth = 1.0
        femaleSexChoice.layer.borderColor = #colorLiteral(red: 1.0, green: 1.0, blue: 1.0, alpha: 1.0)
        
        otherSexChoice.layer.cornerRadius = 5.0
        otherSexChoice.layer.borderWidth = 1.0
        otherSexChoice.layer.borderColor = #colorLiteral(red: 1.0, green: 1.0, blue: 1.0, alpha: 1.0)
        
        registrationButton.layer.cornerRadius = 5.0
        registrationButton.layer.borderWidth = 1.0
        registrationButton.layer.borderColor = #colorLiteral(red: 1.0, green: 1.0, blue: 1.0, alpha: 1.0)
        
        let request: NSFetchRequest<User> = User.fetchRequest()
        guard let user = try? AppDelegate.viewContext.fetch(request) else {
            return
        }
        
        if (user.count > 0) {
            for userData in user {
                userData.savedEmail! = ""
                userData.savedPassword! = ""
            }
            try? AppDelegate.viewContext.save()
        }
    }
    
    override func viewDidAppear(_ animated: Bool) {
        scrollView.contentSize = CGSize(width: scrollView.frame.width, height: scrollView.frame.height + 500.0)
        scrollView.delegate = self
        scrollView.isScrollEnabled = true
        scrollView.isUserInteractionEnabled = true
        scrollView.touchesShouldCancel(in: contentView)
        scrollView.delaysContentTouches = false
    }
    
    func textFieldShouldReturn(_ textField: UITextField) -> Bool {
        lastNameField.resignFirstResponder()
        firstNameField.resignFirstResponder()
        emailField.resignFirstResponder()
        passwordField.resignFirstResponder()
        passwordRepeatField.resignFirstResponder()
        return true
    }
    
    func setTextFieldToError(_ field: UIView) {
        field.layer.borderWidth = 2.0
        field.layer.borderColor = #colorLiteral(red: 0.8078431487, green: 0.02745098062, blue: 0.3333333433, alpha: 1)
    }
    
    func setTextFieldToSuccess(_ field: UIView) {
        field.layer.borderWidth = 2.0
        field.layer.borderColor = #colorLiteral(red: 0.2745098174, green: 0.4862745106, blue: 0.1411764771, alpha: 1)
    }
    
    func setFieldToNormal(_ field: UIView) {
        field.layer.borderWidth = 2.0
        field.layer.borderColor = #colorLiteral(red: 1, green: 1, blue: 1, alpha: 1)
    }
    
    @IBAction func onMaleSexButtonClicked(_ sender: UIButton) {
        self.setFieldToNormal(femaleSexChoice)
        self.setFieldToNormal(otherSexChoice)
        
        if (Registration.sexChoice == .noSex || Registration.sexChoice == .female || Registration.sexChoice == .another) {
            Registration.sexChoice = .male
            
            self.setTextFieldToSuccess(maleSexChoice)
            self.isManSexChoose.image = #imageLiteral(resourceName: "success")
            self.isManSexChoose.isHidden = false
            
            self.setFieldToNormal(femaleSexChoice)
            self.isFemaleSexChoose.isHidden = true
            
            self.setFieldToNormal(otherSexChoice)
            self.isAnotherSexChoice.isHidden = true
        }
    }
    
    @IBAction func onFemaleSexButtonClicked(_ sender: UIButton) {
        self.setFieldToNormal(maleSexChoice)
        self.setFieldToNormal(otherSexChoice)
        
        if (Registration.sexChoice == .noSex || Registration.sexChoice == .male || Registration.sexChoice == .another) {
            Registration.sexChoice = .female
            
            self.setTextFieldToSuccess(femaleSexChoice)
            self.isFemaleSexChoose.image = #imageLiteral(resourceName: "success")
            self.isFemaleSexChoose.isHidden = false
            
            self.setFieldToNormal(maleSexChoice)
            self.isManSexChoose.isHidden = true
            
            self.setFieldToNormal(otherSexChoice)
            self.isAnotherSexChoice.isHidden = true
        }
        
        
    }
    
    @IBAction func onAnotherSexButtonClicked(_ sender: UIButton) {
        self.setFieldToNormal(maleSexChoice)
        self.setFieldToNormal(femaleSexChoice)
        
        if (Registration.sexChoice == .noSex || Registration.sexChoice == .male || Registration.sexChoice == .female) {
            Registration.sexChoice = .another
            
            self.setTextFieldToSuccess(otherSexChoice)
            self.isAnotherSexChoice.image = #imageLiteral(resourceName: "success")
            self.isAnotherSexChoice.isHidden = false
            
            self.setFieldToNormal(maleSexChoice)
            self.isManSexChoose.isHidden = true
            
            self.setFieldToNormal(femaleSexChoice)
            self.isFemaleSexChoose.isHidden = true
        }
    }
    
    
    @IBAction func onRegistrationButtonClicked(_ sender: UIButton) {
        if (Registration.sexChoice == .noSex) {
            self.setTextFieldToError(maleSexChoice)
            self.isManSexChoose.image = #imageLiteral(resourceName: "error")
            self.isManSexChoose.isHidden = false
            
            self.setTextFieldToError(femaleSexChoice)
            self.isFemaleSexChoose.image = #imageLiteral(resourceName: "error")
            self.isFemaleSexChoose.isHidden = false
            
            self.setTextFieldToError(otherSexChoice)
            self.isAnotherSexChoice.image = #imageLiteral(resourceName: "error")
            self.isAnotherSexChoice.isHidden = false
            
            Registration.isValidRegistration = false
        } else {
            Registration.isValidRegistration = true
        }
        
        if(lastNameField.text! == "") {
            setTextFieldToError(lastNameView)
            lastNameFieldState.image = #imageLiteral(resourceName: "error")
            lastNameFieldState.isHidden = false
            
            Registration.isValidRegistration = false
        } else {
            setTextFieldToSuccess(lastNameView)
            lastNameFieldState.image = #imageLiteral(resourceName: "success")
            lastNameFieldState.isHidden = false
            
            Registration.isValidRegistration = true
        }
        
        if(firstNameField.text! == "") {
            setTextFieldToError(firstNameView)
            firstNameFieldState.image = #imageLiteral(resourceName: "error")
            firstNameFieldState.isHidden = false
            
            Registration.isValidRegistration = false
        } else {
            setTextFieldToSuccess(firstNameView)
            firstNameFieldState.image = #imageLiteral(resourceName: "success")
            firstNameFieldState.isHidden = false
            
            Registration.isValidRegistration = true
        }
        
        if(emailField.text! == "") {
            setTextFieldToError(emailView)
            emailFieldState.image = #imageLiteral(resourceName: "error")
            emailFieldState.isHidden = false
            
            Registration.isValidRegistration = false
        } else {
            setTextFieldToSuccess(emailView)
            emailFieldState.image = #imageLiteral(resourceName: "success")
            emailFieldState.isHidden = false
            
            Registration.isValidRegistration = true
        }
        
        if(passwordField.text! == "") {
            setTextFieldToError(passwordView)
            passwordFieldState.image = #imageLiteral(resourceName: "error")
            passwordFieldState.isHidden = false
            
            Registration.isValidRegistration = false
        } else {
            setTextFieldToSuccess(passwordView)
            passwordFieldState.image = #imageLiteral(resourceName: "success")
            passwordFieldState.isHidden = false
            
            Registration.isValidRegistration = true
        }
        
        if(passwordRepeatField.text! == "") {
            setTextFieldToError(passwordRepeatView)
            passwordRepeatFieldState.image = #imageLiteral(resourceName: "error")
            passwordRepeatFieldState.isHidden = false
            
            Registration.isValidRegistration = false
        } else {
            setTextFieldToSuccess(passwordRepeatView)
            passwordRepeatFieldState.image = #imageLiteral(resourceName: "success")
            passwordRepeatFieldState.isHidden = false
            
            Registration.isValidRegistration = true
        }
        
        if (Registration.isValidRegistration) {
            if (lastNameField.text?.isValidText() ?? false) {
                if (firstNameField.text?.isValidText() ?? false) {
                    if (emailField.text?.isValidEmail() ?? false) {
                        if (passwordField.text?.count ?? 0 > 5) {
                            if (passwordField.text == passwordRepeatField.text) {
                                
                            } else {
                                showErrorView("Les deux mots de passe saisis ne sont pas identiques ! Nous vous prions de vérifier l'ensemble des caractères saisis, y compris les majuscules et minuscules.")
                            }
                        } else {
                            showErrorView("Votre mot de passe doit faire au moins 6 caractères !")
                        }
                    } else {
                        showErrorView("Votre adresse email ne respecte pas le format demandé ! Celle-ci doit être au format 'john.doe@gmail.com' par exemple.")
                    }
                } else {
                    showErrorView("Votre prénom ne respecte pas le format demandé ! Celui-ci doit débuter obligatoirement par une lettre majuscule ! Les prénoms par extension sont acceptés.")
                }
            } else {
                showErrorView("Votre nom ne respecte pas le format demandé ! Celui-ci doit débuter obligatoirement par une lettre majuscule ! Les noms par extension sont acceptés.")
            }
        } else {
            showErrorView("Veuillez saisir tous les champs !")
        }
        
    }
    
    func showErrorView(_ content: String) {
        loadingIndicator.isHidden = true
        loadingView.isHidden = false
        errorViewContent.text = content
        errorView.isHidden = false
    }
    
    @IBAction func closeErrorView(_ sender: UIButton) {
        errorView.isHidden = true
        loadingIndicator.isHidden = true
        loadingView.isHidden = true
    }

}
