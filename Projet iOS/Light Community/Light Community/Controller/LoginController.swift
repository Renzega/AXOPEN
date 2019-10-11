//
//  ViewController.swift
//  Light Community
//
//  Created by Samuel Clauzon on 09/06/2019.
//  Copyright © 2019 Samuel Clauzon. All rights reserved.
//

import UIKit
import CoreData

class ViewController: UIViewController, UITextFieldDelegate {

    @IBOutlet weak var logo: UIImageView!
    
    @IBOutlet weak var contentView: UIView!
    
    @IBOutlet weak var emailView: UIView!
    
    @IBOutlet weak var passwordView: UIView!
    
    @IBOutlet weak var loginButton: UIButton!
    
    @IBOutlet weak var emailField: UITextField!
    
    @IBOutlet weak var emailConfirmationState: UIImageView!
    
    @IBOutlet weak var passwordField: UITextField!
    
    @IBOutlet weak var passwordConfirmationState: UIImageView!
    
    @IBOutlet weak var loadingView: UIView!
    
    @IBOutlet weak var loadingIndicator: UIActivityIndicatorView!
    
    @IBOutlet weak var errorView: UIView!
    
    @IBOutlet weak var errorViewContent: UILabel!
    
    @IBOutlet weak var forgotPasswordButton: UIButton!
    
    private var appKey: String = "192a360f1358b1b7c5c0399fa8683a92"
    private var urlPath: String!
    
    override func viewDidLoad() {
        super.viewDidLoad()
        
        loadingView.isHidden = true
        errorView.isHidden = true
        
        emailField.delegate = self
        passwordField.delegate = self
        
        contentView.isHidden = true
        contentView.isUserInteractionEnabled = false
        
        emailConfirmationState.isHidden = true
        passwordConfirmationState.isHidden = true
        
        contentView.layer.cornerRadius = 5.0
        
        emailView.layer.cornerRadius = 5.0
        emailView.layer.borderWidth = 1.0
        emailView.layer.borderColor = #colorLiteral(red: 0, green: 0, blue: 0, alpha: 1)
        
        passwordView.layer.cornerRadius = 5.0
        passwordView.layer.borderWidth = 1.0
        passwordView.layer.borderColor = #colorLiteral(red: 0, green: 0, blue: 0, alpha: 1)
        
        emailField.placeholderColor = #colorLiteral(red: 0, green: 0, blue: 0, alpha: 1)
        passwordField.placeholderColor = #colorLiteral(red: 0, green: 0, blue: 0, alpha: 1)
        
        errorView.layer.cornerRadius = 5.0
        
        forgotPasswordButton.layer.cornerRadius = 5.0
        forgotPasswordButton.layer.borderWidth = 1.0
        forgotPasswordButton.layer.borderColor = #colorLiteral(red: 0, green: 0, blue: 0, alpha: 1)
        
        loginButton.layer.cornerRadius = 5.0
        loginButton.layer.borderWidth = 1.0
        loginButton.layer.borderColor = #colorLiteral(red: 1.0, green: 1.0, blue: 1.0, alpha: 1.0)
        
        loginButton.layer.shadowPath = UIBezierPath(rect: loginButton.bounds).cgPath
        loginButton.layer.shouldRasterize = true
        loginButton.layer.rasterizationScale = UIScreen.main.scale
        
        let request: NSFetchRequest<User> = User.fetchRequest()
        guard let user = try? AppDelegate.viewContext.fetch(request) else {
            return
        }
        
        if (user.count > 0) {
            var emailGet: String = ""
            var passwordGet: String = ""
                    
            for userData in user {
                emailGet = userData.savedEmail!
                passwordGet = userData.savedPassword!
            }
                    
            if (emailGet != "" && passwordGet != "") {
                self.loadingView.isHidden = false
                self.loadingIndicator.isHidden = false
                        
                self.urlPath = "http://light-community.fr/mysql-app/login.php?app_key=\(self.appKey)&email=\(emailGet)&password=\(passwordGet)"
                        
                let url: URL = URL(string: self.urlPath)!
                let defaultSession = Foundation.URLSession(configuration: URLSessionConfiguration.default)
                        
                let task = defaultSession.dataTask(with: url) { (data, response, error) in
                    if (error != nil) {
                        DispatchQueue.main.async {
                            self.loadingView.isHidden = true
                            self.loadingIndicator.isHidden = true
                                    
                            let request: NSFetchRequest<User> = User.fetchRequest()
                            guard let user = try? AppDelegate.viewContext.fetch(request) else {
                                return
                            }
                            for userData in user {
                                userData.savedEmail! = ""
                                userData.savedPassword! = ""
                            }
                            try? AppDelegate.viewContext.save()
                                    
                            self.showLoginForm()
                            self.contentView.isUserInteractionEnabled = true
                        }
                    } else {
                        LC.parseJSONAlreadyConnected(data!)
                    }
                }
                task.resume()
                        
                let name = Notification.Name("LoginAlreadyConnectedManagerNotification")
                NotificationCenter.default.addObserver(self, selector: #selector(loginAlreadyConnectedManager), name: name, object: nil)
                
            } else {
                self.showLoginForm()
                contentView.isUserInteractionEnabled = true
            }
        } else {
            self.showLoginForm()
            contentView.isUserInteractionEnabled = true
        }
    }
    
    func textFieldShouldReturn(_ textField: UITextField) -> Bool {
        emailField.resignFirstResponder()
        passwordField.resignFirstResponder()
        return true
    }
    
    func showLoginForm() {
        self.contentView.isHidden = false
        UIView.animate(withDuration: 0.8, animations: {
            self.contentView.alpha = 1
        }, completion: { (success) in
            if (success) {
                self.contentView.isUserInteractionEnabled = true
            }
        })
    }
    
    @IBAction func onLoginButtonClicked(_ sender: UIButton) {
        if (emailField.text! != "" && passwordField.text! != "") {
            self.setTextFieldToSuccess(emailView)
            self.setTextFieldToSuccess(passwordView)
            self.emailConfirmationState.isHidden = true
            self.passwordConfirmationState.isHidden = true
            
            if (emailField.text!.isValidEmail()) {
                self.setTextFieldToSuccess(emailView)
                
                self.setTextFieldToSuccess(emailView)
                self.setTextFieldToSuccess(passwordView)
                
                self.emailConfirmationState.image = #imageLiteral(resourceName: "success")
                self.emailConfirmationState.isHidden = false
                self.passwordConfirmationState.image = #imageLiteral(resourceName: "success")
                self.passwordConfirmationState.isHidden = false
                
                self.loadingView.isHidden = false
                self.loadingIndicator.isHidden = false
                
                urlPath = "http://light-community.fr/mysql-app/login.php?app_key=\(appKey)&email=\(emailField.text!)&password=\(passwordField.text!)"
                
                let url: URL = URL(string: urlPath)!
                let defaultSession = Foundation.URLSession(configuration: URLSessionConfiguration.default)
                
                let task = defaultSession.dataTask(with: url) { (data, response, error) in
                    if (error != nil) {
                        DispatchQueue.main.async {
                            self.loadingIndicator.isHidden = true
                            self.showErrorView("Veuillez vérifier votre connexion internet puis réessayer à nouveau.")
                        }
                    } else {
                        LC.parseJSON(data!)
                    }
                }
                task.resume()
                
                let name = Notification.Name("LoginManagerNotification")
                NotificationCenter.default.addObserver(self, selector: #selector(loginManager), name: name, object: nil)
                
                Login.email = self.emailField.text!
                Login.password = self.passwordField.text!
            } else {
                self.setTextFieldToError(emailView)
                self.emailConfirmationState.image = #imageLiteral(resourceName: "error")
                self.emailConfirmationState.isHidden = false
            }
            
        } else {
            if (emailField.text! != "") {
                self.setTextFieldToSuccess(emailView)
                self.setTextFieldToError(passwordView)
                
                self.emailConfirmationState.isHidden = true
                self.passwordConfirmationState.image = #imageLiteral(resourceName: "error")
                self.passwordConfirmationState.isHidden = false
            } else {
                self.setTextFieldToError(emailView)
                
                self.emailConfirmationState.image = #imageLiteral(resourceName: "error")
                self.emailConfirmationState.isHidden = false
                
                if (passwordField.text == "") {
                    self.setTextFieldToError(passwordView)
                    
                    self.passwordConfirmationState.image = #imageLiteral(resourceName: "error")
                    self.passwordConfirmationState.isHidden = false
                } else {
                    self.setTextFieldToSuccess(passwordView)
                    
                    self.passwordConfirmationState.isHidden = true
                }
            }
        }
    }
    
    func setTextFieldToError(_ field: UIView) {
        field.layer.borderWidth = 2.0
        field.layer.borderColor = #colorLiteral(red: 0.8078431487, green: 0.02745098062, blue: 0.3333333433, alpha: 1)
    }
    
    func setTextFieldToSuccess(_ field: UIView) {
        field.layer.borderWidth = 2.0
        field.layer.borderColor = #colorLiteral(red: 0.2745098174, green: 0.4862745106, blue: 0.1411764771, alpha: 1)
    }
    
    @objc func loginManager() {
        switch LC.loginState {
        case .successLogin:
            saveLoginInformations(Login.email, Login.password)
            Student.isConnected = true
            loadHomeController()
        case .noAccountVerification:
            print("Compte non-vérifié")
        case .badLogin:
            self.loadingIndicator.isHidden = true
            self.showErrorView("Adresse email ou mot de passe incorrect !")
        }
    }
    
    @objc func loginAlreadyConnectedManager() {
        switch LC.loginState {
        case .successLogin:
            Student.isConnected = true
            let request: NSFetchRequest<User> = User.fetchRequest()
            guard let user = try? AppDelegate.viewContext.fetch(request) else {
                return
            }
            for userData in user {
                Login.email = userData.savedEmail!
                Login.password = userData.savedPassword!
            }
            loadHomeController()
        case .noAccountVerification:
            loadingView.isHidden = true
            loadingIndicator.isHidden = true
            showLoginForm()
        case .badLogin:
            loadingView.isHidden = true
            loadingIndicator.isHidden = true
            showLoginForm()
            let request: NSFetchRequest<User> = User.fetchRequest()
            guard let user = try? AppDelegate.viewContext.fetch(request) else {
                return
            }
            for userData in user {
                userData.savedEmail! = ""
                userData.savedPassword! = ""
            }
            try? AppDelegate.viewContext.save()
        }
    }
    
    func saveLoginInformations(_ email: String, _ password: String) {
        let user = User(context: AppDelegate.viewContext)
        user.savedEmail = email
        user.savedPassword = password
        try? AppDelegate.viewContext.save()
    }
    
    func showErrorView(_ content: String) {
        self.errorViewContent.text = content
        self.errorView.isHidden = false
    }
    
    func closeErrorView() {
        errorView.isHidden = true
        loadingView.isHidden = true
    }
    
    @IBAction func onCloseErrorViewButtonClicked(_ sender: UIButton) {
        closeErrorView()
    }
    
    func loadHomeController() {
        let storyboard = UIStoryboard(name: "Home", bundle: nil)
        let controller = storyboard.instantiateViewController(withIdentifier: "HomeController")
        self.present(controller, animated: true, completion: nil)
    }
    
}

extension UITextField {
    @IBInspectable var placeholderColor: UIColor {
        get {
            return self.attributedPlaceholder?.attribute(.foregroundColor, at: 0, effectiveRange: nil) as? UIColor ?? .lightText
        }
        set {
            self.attributedPlaceholder = NSAttributedString(string: self.placeholder ?? "", attributes: [.foregroundColor: newValue])
        }
    }
}
