//
//  StartPageController.swift
//  Light Community
//
//  Created by Samuel Clauzon on 10/06/2019.
//  Copyright © 2019 Samuel Clauzon. All rights reserved.
//

import UIKit

class StartPageController: UIViewController {

    @IBOutlet weak var logo: UIImageView!
    
    @IBOutlet weak var contentView: UIView!
    
    @IBOutlet weak var registrationButton: UIButton!
    
    @IBOutlet weak var loginButton: UIButton!
    
    override func viewDidLoad() {
        super.viewDidLoad()
        
        contentView.isHidden = true
        
        contentView.isUserInteractionEnabled = false
        
        registrationButton.layer.cornerRadius = 5.0
        
        loginButton.layer.cornerRadius = 5.0
        loginButton.layer.borderWidth = 1.0
        loginButton.layer.borderColor = #colorLiteral(red: 1.0, green: 1.0, blue: 1.0, alpha: 1.0)

        UIImageView.animate(withDuration: 1.0, animations: {
            self.logo.frame = CGRect(origin: CGPoint(x: self.logo.frame.origin.x, y: self.logo.frame.origin.y - 180.0), size: CGSize(width: self.logo.frame.width, height: self.logo.frame.height))
        }) { (success) in
            if (success) {
                self.contentView.isHidden = false
                UIView.animate(withDuration: 0.8, animations: {
                    self.contentView.alpha = 1
                }, completion: { (success) in
                    if (success) {
                        self.contentView.isUserInteractionEnabled = true
                    }
                })
            }
        }
    }
    
    @IBAction func onLogginButtonClicked(_ sender: UIButton) {
        let storyboard = UIStoryboard(name: "Login", bundle: nil)
        let controller = storyboard.instantiateViewController(withIdentifier: "LoginController")
        self.present(controller, animated: true, completion: nil)
    }
    
    @IBAction func onRegistrationButtonClicked(_ sender: UIButton) {
        let storyboard = UIStoryboard(name: "Registration", bundle: nil)
        let controller = storyboard.instantiateViewController(withIdentifier: "RegistrationController")
        self.present(controller, animated: true, completion: nil)
    }
    
}
